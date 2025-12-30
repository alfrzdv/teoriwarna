<?php

namespace App\Http\Controllers;

use App\Mail\AdminNewOrder;
use App\Mail\OrderConfirmation;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('warning', 'Silakan login terlebih dahulu untuk melanjutkan checkout.')
                ->with('intended', route('checkout.index'));
        }

        $cart = Cart::where('user_id', Auth::id())->first();

        if (!$cart || $cart->cart_items()->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
        }

        $cartItems = $cart->cart_items()->with(['product.product_images'])->get();

        // Check stock availability
        foreach ($cartItems as $item) {
            if (!$item->product->hasEnoughStock($item->quantity)) {
                return redirect()->route('cart.index')
                    ->with('error', "Stok {$item->product->name} tidak mencukupi.");
            }
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $addresses = UserAddress::where('user_id', Auth::id())->get();

        return view('checkout.index', compact('cartItems', 'subtotal', 'addresses'));
    }

    public function buyNow(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if (!$product->isActive()) {
            return back()->with('error', 'Produk tidak tersedia.');
        }

        if (!$product->hasEnoughStock($validated['quantity'])) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        // Store buy now data in session
        session([
            'buy_now' => [
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'price' => $product->price,
            ]
        ]);

        return redirect()->route('checkout.buy-now');
    }

    public function buyNowCheckout()
    {
        $buyNowData = session('buy_now');

        if (!$buyNowData) {
            return redirect()->route('products.index')->with('error', 'Data pembelian tidak ditemukan.');
        }

        $product = Product::with(['product_images'])->findOrFail($buyNowData['product_id']);

        if (!$product->hasEnoughStock($buyNowData['quantity'])) {
            session()->forget('buy_now');
            return redirect()->route('products.show', $product)
                ->with('error', 'Stok tidak mencukupi.');
        }

        $subtotal = $buyNowData['quantity'] * $buyNowData['price'];
        $addresses = UserAddress::where('user_id', Auth::id())->get();

        // Create temporary item structure like cart items
        $items = collect([
            (object)[
                'product' => $product,
                'quantity' => $buyNowData['quantity'],
                'price' => $buyNowData['price'],
            ]
        ]);

        return view('checkout.buy-now', compact('items', 'subtotal', 'addresses', 'product'));
    }

    public function processBuyNow(Request $request)
    {
        $buyNowData = session('buy_now');

        if (!$buyNowData) {
            return redirect()->route('products.index')->with('error', 'Data pembelian tidak ditemukan.');
        }

        $validated = $request->validate([
            'address_id' => 'nullable|exists:user_addresses,id',
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:255',
            'shipping_postal_code' => 'required|string|max:10',
            'shipping_method' => 'required|in:regular,express,same_day',
            'payment_method' => 'required|in:bank_transfer,e_wallet,cod',
            'notes' => 'nullable|string|max:500',
        ]);

        $product = Product::findOrFail($buyNowData['product_id']);

        if (!$product->hasEnoughStock($buyNowData['quantity'])) {
            session()->forget('buy_now');
            return redirect()->route('products.show', $product)
                ->with('error', 'Stok tidak mencukupi.');
        }

        DB::beginTransaction();

        try {
            $subtotal = $buyNowData['quantity'] * $buyNowData['price'];

            $shipping_cost = match($validated['shipping_method']) {
                'regular' => 15000,
                'express' => 30000,
                'same_day' => 50000,
                default => 15000
            };

            $total = $subtotal + $shipping_cost;

            $order = Order::create([
                'user_id' => Auth::id(),
                'address_id' => $validated['address_id'] ?? null,
                'order_number' => $this->generateOrderNumber(),
                'total_amount' => $total,
                'shipping_cost' => $shipping_cost,
                'shipping_method' => $validated['shipping_method'],
                'status' => 'pending',
                'shipping_name' => $validated['shipping_name'],
                'shipping_phone' => $validated['shipping_phone'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_postal_code' => $validated['shipping_postal_code'],
                'notes' => $validated['notes'] ?? null,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $buyNowData['quantity'],
                'price' => $buyNowData['price'],
            ]);

            $product->reduceStock($buyNowData['quantity'], "Order #{$order->order_number}");

            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $validated['payment_method'],
                'amount' => $total,
                'status' => 'pending',
            ]);

            session()->forget('buy_now');

            DB::commit();

            // Send emails (wrapped in try-catch to prevent checkout failure)
            try {
                Mail::to($order->user->email)->send(new OrderConfirmation($order));

                $adminUsers = User::where('is_admin', true)->get();
                foreach ($adminUsers as $admin) {
                    Mail::to($admin->email)->send(new AdminNewOrder($order));
                }
            } catch (\Exception $emailError) {
                \Log::warning('Email sending failed but order created: ' . $emailError->getMessage());
                // Continue anyway - email failure shouldn't block order
            }

            return redirect()->route('orders.show', $order)
                ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Buy Now Checkout Error: ' . $e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function applyCoupon(Request $request)
    {
        $validated = $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $coupon = Coupon::where('code', $validated['coupon_code'])->first();

        if (!$coupon) {
            return back()->with('error', 'Kode kupon tidak valid.');
        }

        if (!$coupon->isValid()) {
            return back()->with('error', 'Kupon tidak berlaku atau sudah kadaluarsa.');
        }

        if (!$coupon->canBeUsedBy(Auth::id())) {
            return back()->with('error', 'Anda sudah mencapai batas penggunaan kupon ini.');
        }

        $cart = Cart::where('user_id', Auth::id())->first();
        $cartItems = $cart->cart_items()->with(['product'])->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $discount = $coupon->calculateDiscount($subtotal);

        if ($discount == 0) {
            return back()->with('error', "Minimal pembelian untuk kupon ini adalah Rp " . number_format($coupon->min_purchase, 0, ',', '.'));
        }

        session(['applied_coupon' => [
            'code' => $coupon->code,
            'discount' => $discount,
            'coupon_id' => $coupon->id,
        ]]);

        return back()->with('success', "Kupon berhasil diterapkan! Diskon: Rp " . number_format($discount, 0, ',', '.'));
    }

    public function removeCoupon()
    {
        session()->forget('applied_coupon');
        return back()->with('success', 'Kupon berhasil dihapus.');
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'address_id' => 'nullable|exists:user_addresses,id',
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:255',
            'shipping_postal_code' => 'required|string|max:10',
            'shipping_method' => 'required|in:regular,express,same_day',
            'payment_method' => 'required|in:bank_transfer,e_wallet,cod',
            'notes' => 'nullable|string|max:500',
        ]);

        $cart = Cart::where('user_id', Auth::id())->first();

        if (!$cart || $cart->cart_items()->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
        }

        $cartItems = $cart->cart_items()->with(['product'])->get();

        // Check stock availability again
        foreach ($cartItems as $item) {
            if (!$item->product->hasEnoughStock($item->quantity)) {
                return redirect()->route('cart.index')
                    ->with('error', "Stok {$item->product->name} tidak mencukupi.");
            }
        }

        DB::beginTransaction();

        try {
            // Calculate totals
            $subtotal = $cartItems->sum(function ($item) {
                return $item->quantity * $item->price;
            });

            // Calculate shipping cost based on method
            $shipping_cost = match($validated['shipping_method']) {
                'regular' => 15000,
                'express' => 30000,
                'same_day' => 50000,
                default => 15000
            };

            // Apply coupon if exists
            $discount = 0;
            $couponCode = null;
            $couponId = null;

            if (session('applied_coupon')) {
                $appliedCoupon = session('applied_coupon');
                $coupon = Coupon::find($appliedCoupon['coupon_id']);

                if ($coupon && $coupon->isValid() && $coupon->canBeUsedBy(Auth::id())) {
                    $discount = $coupon->calculateDiscount($subtotal);
                    $couponCode = $coupon->code;
                    $couponId = $coupon->id;
                }
            }

            $total = $subtotal + $shipping_cost - $discount;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'address_id' => $validated['address_id'] ?? null,
                'order_number' => $this->generateOrderNumber(),
                'subtotal' => $subtotal,
                'total_amount' => $total,
                'shipping_cost' => $shipping_cost,
                'shipping_method' => $validated['shipping_method'],
                'discount_amount' => $discount,
                'coupon_code' => $couponCode,
                'status' => 'pending',
                'shipping_name' => $validated['shipping_name'],
                'shipping_phone' => $validated['shipping_phone'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_postal_code' => $validated['shipping_postal_code'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Create order items and reduce stock
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                ]);

                // Reduce stock
                $cartItem->product->reduceStock($cartItem->quantity, "Order #{$order->order_number}");
            }

            // Create payment record
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $validated['payment_method'],
                'amount' => $total,
                'status' => 'pending',
            ]);

            // Record coupon usage
            if ($couponId) {
                CouponUsage::create([
                    'coupon_id' => $couponId,
                    'user_id' => Auth::id(),
                    'order_id' => $order->id,
                    'discount_amount' => $discount,
                ]);

                $coupon->incrementUsage();
                session()->forget('applied_coupon');
            }

            // Clear cart
            $cart->cart_items()->delete();

            DB::commit();

            // Send emails (wrapped in try-catch to prevent checkout failure)
            try {
                Mail::to($order->user->email)->send(new OrderConfirmation($order));

                $adminUsers = User::where('is_admin', true)->get();
                foreach ($adminUsers as $admin) {
                    Mail::to($admin->email)->send(new AdminNewOrder($order));
                }
            } catch (\Exception $emailError) {
                \Log::warning('Email sending failed but order created: ' . $emailError->getMessage());
                // Continue anyway - email failure shouldn't block order
            }

            return redirect()->route('orders.show', $order)
                ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout Error: ' . $e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = date('Ymd');
        $random = strtoupper(Str::random(6));

        return "{$prefix}-{$date}-{$random}";
    }
}
