<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            // Get cart from session for guests
            $sessionCart = session()->get('cart', []);
            $cartItems = collect();
            $subtotal = 0;

            foreach ($sessionCart as $item) {
                $product = Product::with(['product_images', 'category'])->find($item['product_id']);
                if ($product) {
                    $cartItem = (object)[
                        'id' => 'session_' . $item['product_id'],
                        'product' => $product,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ];
                    $cartItems->push($cartItem);
                    $subtotal += $cartItem->subtotal;
                }
            }

            return view('cart.index', compact('cartItems', 'subtotal'));
        }

        $cart = $this->getOrCreateCart();
        $cartItems = $cart->cart_items()->with(['product.product_images', 'product.category'])->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    public function add(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if (!$product->isActive()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Produk tidak tersedia.'], 400);
            }
            return back()->with('error', 'Produk tidak tersedia.');
        }

        if (!$product->hasEnoughStock($validated['quantity'])) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi.'], 400);
            }
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        // Handle guest users with session-based cart
        if (!Auth::check()) {
            $cart = session()->get('cart', []);

            // Check if product already in session cart
            $found = false;
            foreach ($cart as $key => $item) {
                if ($item['product_id'] == $product->id) {
                    $newQuantity = $item['quantity'] + $validated['quantity'];

                    if (!$product->hasEnoughStock($newQuantity)) {
                        if ($request->expectsJson()) {
                            return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi untuk jumlah yang diminta.'], 400);
                        }
                        return back()->with('error', 'Stok tidak mencukupi untuk jumlah yang diminta.');
                    }

                    $cart[$key]['quantity'] = $newQuantity;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $cart[] = [
                    'product_id' => $product->id,
                    'quantity' => $validated['quantity'],
                    'price' => $product->price,
                ];
            }

            session()->put('cart', $cart);

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Produk berhasil ditambahkan ke keranjang.']);
            }
            return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
        }

        // Handle authenticated users with database cart
        $cart = $this->getOrCreateCart();

        // Check if product already in cart
        $cartItem = $cart->cart_items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            // Update quantity
            $newQuantity = $cartItem->quantity + $validated['quantity'];

            if (!$product->hasEnoughStock($newQuantity)) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi untuk jumlah yang diminta.'], 400);
                }
                return back()->with('error', 'Stok tidak mencukupi untuk jumlah yang diminta.');
            }

            $cartItem->update([
                'quantity' => $newQuantity,
                'subtotal' => $cartItem->price * $newQuantity,
            ]);

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Jumlah produk di keranjang berhasil diupdate.']);
            }
            return back()->with('success', 'Jumlah produk di keranjang berhasil diupdate.');
        } else {
            // Add new item to cart
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'price' => $product->price,
                'subtotal' => $product->price * $validated['quantity'],
            ]);

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Produk berhasil ditambahkan ke keranjang.']);
            }
            return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
        }
    }

    public function update(Request $request, CartItem $cartItem)
    {
        // Ensure cart item belongs to current user
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if (!$cartItem->product->hasEnoughStock($validated['quantity'])) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $cartItem->update([
            'quantity' => $validated['quantity'],
            'subtotal' => $cartItem->price * $validated['quantity'],
        ]);

        return back()->with('success', 'Keranjang berhasil diupdate.');
    }

    public function updateSession(Request $request, $productId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);
        $product = Product::find($productId);

        if (!$product || !$product->hasEnoughStock($validated['quantity'])) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        foreach ($cart as $key => $item) {
            if ($item['product_id'] == $productId) {
                $cart[$key]['quantity'] = $validated['quantity'];
                break;
            }
        }

        session()->put('cart', $cart);
        return back()->with('success', 'Keranjang berhasil diupdate.');
    }

    public function remove(CartItem $cartItem)
    {
        // Ensure cart item belongs to current user
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    public function removeSession($productId)
    {
        $cart = session()->get('cart', []);

        foreach ($cart as $key => $item) {
            if ($item['product_id'] == $productId) {
                unset($cart[$key]);
                break;
            }
        }

        session()->put('cart', array_values($cart));
        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    public function clear()
    {
        if (!Auth::check()) {
            session()->forget('cart');
            return back()->with('success', 'Keranjang berhasil dikosongkan.');
        }

        $cart = $this->getOrCreateCart();
        $cart->cart_items()->delete();

        return back()->with('success', 'Keranjang berhasil dikosongkan.');
    }

    public function count()
    {
        if (!Auth::check()) {
            $count = count(session()->get('cart', []));
            return response()->json(['count' => $count]);
        }

        $cart = $this->getOrCreateCart();
        $count = $cart->cart_items()->count();

        return response()->json(['count' => $count]);
    }

    private function getOrCreateCart()
    {
        $cart = Cart::where('user_id', Auth::id())->first();

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => Auth::id(),
            ]);
        }

        return $cart;
    }
}
