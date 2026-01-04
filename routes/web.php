<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductCatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('home');
})->name('home');

// Product Catalog (Public/User)
Route::get('/catalog', [ProductCatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{product}', [ProductCatalogController::class, 'show'])->name('catalog.show');

// Legacy route redirects
Route::get('/products', function () {
    return redirect()->route('catalog.index');
})->name('products.index');
Route::get('/products/{product}', function ($product) {
    return redirect()->route('catalog.show', $product);
})->name('products.show');

// Cart - Guest can access with session-based cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/session/{productId}', [CartController::class, 'updateSession'])->name('cart.update-session');
Route::delete('/cart/session/{productId}', [CartController::class, 'removeSession'])->name('cart.remove-session');

Route::middleware('auth')->group(function () {
    // Cart (Auth only)
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.picture.update');
    Route::delete('/profile/picture', [ProfileController::class, 'deleteProfilePicture'])->name('profile.picture.delete');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Addresses
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::get('/addresses/create', [AddressController::class, 'create'])->name('addresses.create');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::get('/addresses/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::patch('/addresses/{address}/set-default', [AddressController::class, 'setDefault'])->name('addresses.set-default');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');

    // Reviews
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/create/{orderItem}', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews/{orderItem}', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Routes that require verified email
Route::middleware(['auth', 'verified'])->group(function () {
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

    // Buy Now
    Route::post('/buy-now/{product}', [CheckoutController::class, 'buyNow'])->name('buy-now');
    Route::get('/checkout/buy-now', [CheckoutController::class, 'buyNowCheckout'])->name('checkout.buy-now');
    Route::post('/checkout/buy-now/process', [CheckoutController::class, 'processBuyNow'])->name('checkout.buy-now.process');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');
    Route::post('/orders/{order}/request-refund', [OrderController::class, 'requestRefund'])->name('orders.request-refund');
    Route::get('/orders/{order}/refund', [OrderController::class, 'viewRefund'])->name('orders.refund');

    // Payment
    Route::post('/payment/{order}/snap-token', [PaymentController::class, 'createSnapToken'])->name('payment.snap-token');
    Route::get('/payment/{order}/finish', [PaymentController::class, 'finish'])->name('payment.finish');
    Route::get('/payment/{order}/status', [PaymentController::class, 'checkStatus'])->name('payment.status');
});

// Midtrans Webhook (no auth required)
Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');
        



require __DIR__.'/auth.php';
