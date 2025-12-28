<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Shopping Cart
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/cart/cart.css') }}">

    <div class="cart-container">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if($cartItems->count() > 0)
            <div class="cart-grid">
                <!-- Cart Items -->
                <div class="cart-items-section">
                    <div class="cart-header">
                        <h2 class="cart-title">Your Cart ({{ $cartItems->count() }} items)</h2>
                    </div>

                    @foreach($cartItems as $item)
                        <div class="cart-item">
                            <div class="cart-item-image">
                                @if($item->product->getPrimaryImage())
                                    <img src="{{ asset('storage/' . $item->product->getPrimaryImage()->image_path) }}"
                                        alt="{{ $item->product->name }}" class="cart-item-img">
                                @else
                                    <div class="cart-item-img" style="display: flex; align-items: center; justify-content: center; background: #e5e7eb;">
                                        <span style="font-size: 2rem;">📦</span>
                                    </div>
                                @endif
                            </div>

                            <div class="cart-item-details">
                                <h3 class="cart-item-name">{{ $item->product->name }}</h3>
                                <p class="cart-item-category">{{ $item->product->category->name }}</p>
                                <p class="cart-item-price">Rp {{ number_format($item->price, 0, ',', '.') }}</p>

                                <div class="cart-item-actions">
                                    <form action="{{ route('cart.update', $item) }}" method="POST" class="quantity-control">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" name="quantity" value="{{ max(1, $item->quantity - 1) }}" class="quantity-btn">−</button>
                                        <span class="quantity-value">{{ $item->quantity }}</span>
                                        <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="quantity-btn">+</button>
                                    </form>

                                    <form action="{{ route('cart.remove', $item) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="remove-btn" onclick="return confirm('Remove this item?')">Remove</button>
                                    </form>
                                </div>
                            </div>

                            <div class="cart-item-right">
                                <p class="cart-item-subtotal">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Cart Summary -->
                <div class="cart-summary">
                    <h3 class="summary-title">Order Summary</h3>

                    <div class="summary-row">
                        <span class="summary-label">Subtotal ({{ $cartItems->count() }} items)</span>
                        <span class="summary-value">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Shipping</span>
                        <span class="summary-value">Calculated at checkout</span>
                    </div>

                    <div class="summary-total">
                        <span>Total</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="checkout-btn">Proceed to Checkout</a>
                    <a href="{{ route('products.index') }}" class="continue-shopping">Continue Shopping</a>

                    <form action="{{ route('cart.clear') }}" method="POST" style="margin-top: 1rem;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="continue-shopping" style="background-color: #fee2e2; color: #991b1b;" onclick="return confirm('Clear all items from cart?')">
                            Clear Cart
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="cart-items-section">
                <div class="empty-cart">
                    <div class="empty-cart-icon">🛒</div>
                    <h2 class="empty-cart-title">Your cart is empty</h2>
                    <p class="empty-cart-text">Add some products to get started!</p>
                    <a href="{{ route('products.index') }}" class="shop-now-btn">Start Shopping</a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
