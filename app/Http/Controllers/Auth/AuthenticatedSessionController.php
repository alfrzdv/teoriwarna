<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Merge guest cart with user cart
        $this->mergeGuestCart();

        // Redirect based on role
        if (auth()->user()->hasAdminAccess()) {
            return redirect()->route('admin.dashboard');
        }

        // Check if there's an intended URL (from checkout redirect)
        if (session()->has('intended')) {
            $intended = session()->pull('intended');
            return redirect($intended);
        }

        return redirect()->route('products.index');
    }

    /**
     * Merge guest session cart with authenticated user's database cart
     */
    private function mergeGuestCart()
    {
        $sessionCart = session()->get('cart', []);

        if (empty($sessionCart)) {
            return;
        }

        // Get or create user's cart
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        foreach ($sessionCart as $item) {
            $product = Product::find($item['product_id']);

            if (!$product || !$product->isActive()) {
                continue;
            }

            // Check if product already exists in user's cart
            $cartItem = $cart->cart_items()->where('product_id', $product->id)->first();

            if ($cartItem) {
                // Update quantity if product exists
                $newQuantity = $cartItem->quantity + $item['quantity'];

                // Check stock availability
                if ($product->hasEnoughStock($newQuantity)) {
                    $cartItem->update([
                        'quantity' => $newQuantity,
                        'subtotal' => $cartItem->price * $newQuantity,
                    ]);
                }
            } else {
                // Add new item if stock is available
                if ($product->hasEnoughStock($item['quantity'])) {
                    CartItem::create([
                        'cart_id' => $cart->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);
                }
            }
        }

        // Clear session cart
        session()->forget('cart');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
