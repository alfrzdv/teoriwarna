<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
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

    private function mergeGuestCart()
    {
        $guestCart = session()->get('cart', []);

        if (empty($guestCart)) {
            return;
        }

        $cart = \App\Models\Cart::firstOrCreate(['user_id' => auth()->id()]);

        foreach ($guestCart as $productId => $item) {
            $product = \App\Models\Product::find($productId);

            if (!$product || !$product->isActive()) {
                continue;
            }

            $cartItem = $cart->cart_items()->where('product_id', $productId)->first();

            if ($cartItem) {
                // Merge quantities
                $newQuantity = $cartItem->quantity + $item['quantity'];

                if ($product->hasEnoughStock($newQuantity)) {
                    $cartItem->update([
                        'quantity' => $newQuantity,
                        'subtotal' => $cartItem->price * $newQuantity,
                    ]);
                }
            } else {
                // Add new item
                if ($product->hasEnoughStock($item['quantity'])) {
                    \App\Models\CartItem::create([
                        'cart_id' => $cart->id,
                        'product_id' => $productId,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);
                }
            }
        }

        // Clear guest cart from session
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
