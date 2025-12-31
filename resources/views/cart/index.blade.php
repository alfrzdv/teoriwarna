<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white leading-tight font-poppins">
            Shopping Cart
        </h2>
    </x-slot>

    <style>
        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }

        .cart-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .cart-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
        }

        @media (max-width: 1024px) {
            .cart-grid {
                grid-template-columns: 1fr;
            }
        }

        .cart-item {
            display: grid;
            grid-template-columns: auto 120px 1fr auto;
            gap: 1.5rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            align-items: center;
        }

        .cart-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .cart-item:nth-child(2n) {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .cart-item:nth-child(3n) {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .cart-item-image {
            width: 120px;
            height: 120px;
            overflow: hidden;
            border-radius: 12px;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .cart-item-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cart-item-details {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .cart-item-name {
            font-size: 1.125rem;
            font-weight: 900;
            color: white;
            text-transform: uppercase;
            letter-spacing: -0.5px;
            margin: 0;
        }

        .cart-item-category {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
            text-transform: uppercase;
            font-weight: 700;
            margin: 0;
        }

        .cart-item-price {
            font-size: 1.25rem;
            font-weight: 900;
            color: white;
            margin: 0.5rem 0;
        }

        .cart-item-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-top: 0.5rem;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.2);
            padding: 0.5rem;
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }

        .quantity-btn {
            width: 32px;
            height: 32px;
            background: white;
            color: black;
            border: none;
            border-radius: 6px;
            font-weight: 900;
            cursor: pointer;
            transition: all 0.2s;
        }

        .quantity-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .quantity-value {
            font-weight: 900;
            color: white;
            min-width: 32px;
            text-align: center;
        }

        .remove-btn {
            padding: 0.5rem 1rem;
            background: rgba(239, 68, 68, 0.9);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s;
        }

        .remove-btn:hover {
            background: rgb(220, 38, 38);
            transform: scale(1.05);
        }

        .cart-item-subtotal {
            font-size: 1.5rem;
            font-weight: 900;
            color: white;
            text-align: right;
            margin: 0;
        }

        .cart-summary {
            background: linear-gradient(135deg, #1e1e1e 0%, #2d2d2d 100%);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 2rem;
            position: sticky;
            top: 2rem;
            height: fit-content;
        }

        .summary-title {
            font-size: 1.5rem;
            font-weight: 900;
            color: white;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: -0.5px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .summary-label {
            color: rgba(255, 255, 255, 0.7);
            font-weight: 600;
        }

        .summary-value {
            color: white;
            font-weight: 700;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.5rem;
            font-weight: 900;
            color: white;
            margin: 1.5rem 0;
            padding: 1.5rem 0;
            border-top: 2px solid rgba(255, 255, 255, 0.2);
        }

        .checkout-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 900;
            font-size: 1rem;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 1rem;
        }

        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .continue-shopping {
            display: block;
            text-align: center;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.875rem;
            text-transform: uppercase;
            transition: all 0.2s;
        }

        .continue-shopping:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .empty-cart {
            text-align: center;
            padding: 4rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .empty-cart-icon {
            font-size: 6rem;
            margin-bottom: 1rem;
        }

        .empty-cart-title {
            font-size: 2rem;
            font-weight: 900;
            color: white;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
        }

        .empty-cart-text {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 2rem;
        }

        .shop-now-btn {
            display: inline-block;
            padding: 1rem 2rem;
            background: white;
            color: black;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 900;
            text-transform: uppercase;
            transition: all 0.3s;
        }

        .shop-now-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
        }

        .cart-title {
            font-size: 1.5rem;
            font-weight: 900;
            color: white;
            text-transform: uppercase;
            margin: 0;
        }

        @media (max-width: 768px) {
            .cart-item {
                grid-template-columns: auto 80px 1fr;
                gap: 1rem;
            }

            .cart-item-image {
                width: 80px;
                height: 80px;
            }

            .cart-item-subtotal {
                grid-column: 2 / -1;
                text-align: left;
                margin-top: 1rem;
            }

            .cart-item-actions {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    <div class="cart-container">
        @if($cartItems->count() > 0)
            <form id="cart-form" action="{{ route('checkout.index') }}" method="GET">
                <div class="cart-grid">
                    <!-- Cart Items -->
                    <div class="cart-items-section">
                        <div class="cart-header">
                            <h2 class="cart-title">Your Cart ({{ $cartItems->count() }} items)</h2>
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" id="select-all" style="cursor: pointer; width: 20px; height: 20px;">
                                <span style="font-size: 0.875rem; color: white; font-weight: 700;">Select All</span>
                            </label>
                        </div>

                        @foreach($cartItems as $item)
                            <div class="cart-item">
                                <div style="display: flex; align-items: center;">
                                    @auth
                                        <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" class="item-checkbox" style="cursor: pointer; width: 20px; height: 20px;">
                                    @else
                                        <input type="checkbox" name="selected_items[]" value="{{ $item->product->id }}" class="item-checkbox" style="cursor: pointer; width: 20px; height: 20px;">
                                    @endauth
                                </div>

                                <div class="cart-item-image">
                                    @if($item->product->getPrimaryImage())
                                        <img src="{{ asset('storage/' . $item->product->getPrimaryImage()->image_path) }}"
                                            alt="{{ $item->product->name }}" class="cart-item-img">
                                    @else
                                        <div class="cart-item-img" style="display: flex; align-items: center; justify-content: center; background: rgba(255, 255, 255, 0.2);">
                                            <span style="font-size: 3rem;">📦</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="cart-item-details">
                                    <h3 class="cart-item-name font-poppins">{{ $item->product->name }}</h3>
                                    <p class="cart-item-category font-poppins">{{ $item->product->category->name }}</p>
                                    <p class="cart-item-price font-poppins">Rp {{ number_format($item->price, 0, ',', '.') }}</p>

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

                                <div class="cart-item-subtotal font-poppins">
                                    Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Cart Summary -->
                    <div class="cart-summary">
                        <h3 class="summary-title font-poppins">Order Summary</h3>

                        <div class="summary-row">
                            <span class="summary-label">Subtotal (<span id="selected-count">0</span> items)</span>
                            <span class="summary-value font-poppins" id="subtotal-display">Rp 0</span>
                        </div>

                        <div class="summary-row">
                            <span class="summary-label">Shipping</span>
                            <span class="summary-value">At checkout</span>
                        </div>

                        <div class="summary-total font-poppins">
                            <span>Total</span>
                            <span id="total-display">Rp 0</span>
                        </div>

                        <button type="submit" class="checkout-btn font-poppins" id="checkout-btn">Checkout Now</button>
                        <a href="{{ route('products.index') }}" class="continue-shopping font-poppins">Continue Shopping</a>

                        <form action="{{ route('cart.clear') }}" method="POST" style="margin-top: 1rem;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="continue-shopping font-poppins" style="background: rgba(239, 68, 68, 0.2); border: 2px solid rgba(239, 68, 68, 0.5);" onclick="return confirm('Clear all items from cart?')">
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
                    <h2 class="empty-cart-title font-poppins">Your cart is empty</h2>
                    <p class="empty-cart-text">Add some products to get started!</p>
                    <a href="{{ route('products.index') }}" class="shop-now-btn font-poppins">Start Shopping</a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
