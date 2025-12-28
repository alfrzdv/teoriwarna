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
            return back()->with('error', 'Produk tidak tersedia.');
        }

        if (!$product->hasEnoughStock($validated['quantity'])) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $cart = $this->getOrCreateCart();

        // Check if product already in cart
        $cartItem = $cart->cart_items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            // Update quantity
            $newQuantity = $cartItem->quantity + $validated['quantity'];

            if (!$product->hasEnoughStock($newQuantity)) {
                return back()->with('error', 'Stok tidak mencukupi untuk jumlah yang diminta.');
            }

            $cartItem->update([
                'quantity' => $newQuantity,
            ]);

            return back()->with('success', 'Jumlah produk di keranjang berhasil diupdate.');
        } else {
            // Add new item to cart
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'price' => $product->price,
            ]);

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
        ]);

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

    public function clear()
    {
        $cart = $this->getOrCreateCart();
        $cart->cart_items()->delete();

        return back()->with('success', 'Keranjang berhasil dikosongkan.');
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
