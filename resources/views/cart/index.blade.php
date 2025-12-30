<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Shopping Cart
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/cart/cart.css') }}">

    <div class="cart-container">
        @if($cartItems->count() > 0)
            <form id="cart-form" action="{{ route('checkout.index') }}" method="GET">
                <div class="cart-grid">
                    <!-- Cart Items -->
                    <div class="cart-items-section">
                        <div class="cart-header">
                            <h2 class="cart-title">Your Cart ({{ $cartItems->count() }} items)</h2>
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" id="select-all" style="cursor: pointer;">
                                <span style="font-size: 0.875rem; color: #6b7280;">Select All</span>
                            </label>
                        </div>

                        @foreach($cartItems as $item)
                            <div class="cart-item">
                                <div style="padding: 1rem; display: flex; align-items: center;">
                                    @auth
                                        <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" class="item-checkbox" style="cursor: pointer;">
                                    @else
                                        <input type="checkbox" name="selected_items[]" value="{{ $item->product->id }}" class="item-checkbox" style="cursor: pointer;">
                                    @endauth
                                </div>
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
                                    @auth
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
                                    @else
                                        <form action="{{ route('cart.update-session', $item->product->id) }}" method="POST" class="quantity-control">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" name="quantity" value="{{ max(1, $item->quantity - 1) }}" class="quantity-btn">−</button>
                                            <span class="quantity-value">{{ $item->quantity }}</span>
                                            <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="quantity-btn">+</button>
                                        </form>

                                        <form action="{{ route('cart.remove-session', $item->product->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="remove-btn" onclick="return confirm('Remove this item?')">Remove</button>
                                        </form>
                                    @endauth
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
                        <span class="summary-label">Subtotal (<span id="selected-count">0</span> items)</span>
                        <span class="summary-value" id="subtotal-display">Rp 0</span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Shipping</span>
                        <span class="summary-value">Calculated at checkout</span>
                    </div>

                    <div class="summary-total">
                        <span>Total</span>
                        <span id="total-display">Rp 0</span>
                    </div>

                    <button type="submit" class="checkout-btn" id="checkout-btn">Proceed to Checkout</button>
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
            </form>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const selectAllCheckbox = document.getElementById('select-all');
                    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
                    const checkoutBtn = document.getElementById('checkout-btn');
                    const cartForm = document.getElementById('cart-form');
                    const selectedCountEl = document.getElementById('selected-count');
                    const subtotalDisplayEl = document.getElementById('subtotal-display');
                    const totalDisplayEl = document.getElementById('total-display');

                    // Item prices mapping
                    const itemPrices = {};
                    @foreach($cartItems as $item)
                        itemPrices['{{ $item->id ?? $item->product->id }}'] = {{ $item->quantity * $item->price }};
                    @endforeach

                    function formatRupiah(amount) {
                        return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    }

                    function updateSummary() {
                        const checkedItems = document.querySelectorAll('.item-checkbox:checked');
                        let total = 0;

                        checkedItems.forEach(checkbox => {
                            const itemId = checkbox.value;
                            if (itemPrices[itemId]) {
                                total += itemPrices[itemId];
                            }
                        });

                        selectedCountEl.textContent = checkedItems.length;
                        subtotalDisplayEl.textContent = formatRupiah(total);
                        totalDisplayEl.textContent = formatRupiah(total);
                    }

                    // Select/deselect all
                    selectAllCheckbox.addEventListener('change', function() {
                        itemCheckboxes.forEach(checkbox => {
                            checkbox.checked = this.checked;
                        });
                        updateSummary();
                    });

                    // Update select all when individual items are changed
                    itemCheckboxes.forEach(checkbox => {
                        checkbox.addEventListener('change', function() {
                            const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
                            selectAllCheckbox.checked = allChecked;
                            updateSummary();
                        });
                    });

                    // Prevent checkout if no items selected
                    cartForm.addEventListener('submit', function(e) {
                        const checkedItems = document.querySelectorAll('.item-checkbox:checked');
                        if (checkedItems.length === 0) {
                            e.preventDefault();
                            alert('Please select at least one item to checkout');
                        }
                    });

                    // Initialize summary on page load
                    updateSummary();
                });
            </script>
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
