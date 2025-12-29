<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
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

            return redirect()->route('orders.show', $order)
                ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
        }
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

            $total = $subtotal + $shipping_cost;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
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

            // Clear cart
            $cart->cart_items()->delete();

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
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
