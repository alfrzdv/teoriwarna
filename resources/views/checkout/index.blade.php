<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Checkout
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/cart/cart.css') }}">

    <div class="checkout-container">
        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf

            <div class="checkout-grid">
                <!-- Checkout Form -->
                <div class="checkout-form">
                    <!-- Shipping Information -->
                    <div class="form-section">
                        <h3 class="section-title">Shipping Information</h3>

                        <div class="form-group">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="shipping_name" value="{{ old('shipping_name', auth()->user()->name) }}"
                                class="form-input" required>
                            @error('shipping_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Phone Number *</label>
                            <input type="tel" name="shipping_phone" value="{{ old('shipping_phone') }}"
                                class="form-input" required>
                            @error('shipping_phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Address *</label>
                            <textarea name="shipping_address" rows="3" class="form-textarea" required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="form-group">
                                <label class="form-label">City *</label>
                                <input type="text" name="shipping_city" value="{{ old('shipping_city') }}"
                                    class="form-input" required>
                                @error('shipping_city')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Postal Code *</label>
                                <input type="text" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}"
                                    class="form-input" required>
                                @error('shipping_postal_code')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Method -->
                    <div class="form-section">
                        <h3 class="section-title">Shipping Method</h3>

                        <div class="payment-methods">
                            <label class="payment-option">
                                <input type="radio" name="shipping_method" value="regular" checked required>
                                <div>
                                    <strong>Regular (3-5 hari)</strong>
                                    <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">Rp 15.000</p>
                                </div>
                            </label>

                            <label class="payment-option">
                                <input type="radio" name="shipping_method" value="express" required>
                                <div>
                                    <strong>Express (1-2 hari)</strong>
                                    <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">Rp 30.000</p>
                                </div>
                            </label>

                            <label class="payment-option">
                                <input type="radio" name="shipping_method" value="same_day" required>
                                <div>
                                    <strong>Same Day</strong>
                                    <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">Rp 50.000</p>
                                </div>
                            </label>
                        </div>

                        @error('shipping_method')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Method -->
                    <div class="form-section">
                        <h3 class="section-title">Payment Method</h3>

                        <div class="payment-methods">
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="bank_transfer" checked required>
                                <div>
                                    <strong>Bank Transfer</strong>
                                    <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">Transfer to our bank account</p>
                                </div>
                            </label>

                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="e_wallet" required>
                                <div>
                                    <strong>E-Wallet</strong>
                                    <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">Pay with OVO, GoPay, Dana, etc.</p>
                                </div>
                            </label>

                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="cod" required>
                                <div>
                                    <strong>Cash on Delivery (COD)</strong>
                                    <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">Pay when the order arrives</p>
                                </div>
                            </label>
                        </div>

                        @error('payment_method')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Additional Notes -->
                    <div class="form-section">
                        <h3 class="section-title">Additional Notes (Optional)</h3>

                        <div class="form-group">
                            <textarea name="notes" rows="3" class="form-textarea" placeholder="Any special requests or delivery instructions...">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="place-order-btn">Place Order</button>
                </div>

                <!-- Order Summary -->
                <div class="order-summary">
                    <h3 class="summary-title">Order Summary</h3>

                    <div style="max-height: 300px; overflow-y: auto; margin-bottom: 1.5rem;">
                        @foreach($cartItems as $item)
                            <div class="order-item">
                                <div class="order-item-image">
                                    @if($item->product->getPrimaryImage())
                                        <img src="{{ asset('storage/' . $item->product->getPrimaryImage()->image_path) }}"
                                            alt="{{ $item->product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #e5e7eb;">
                                            <span>📦</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="order-item-details">
                                    <p class="order-item-name">{{ $item->product->name }}</p>
                                    <p class="order-item-quantity">Qty: {{ $item->quantity }}</p>
                                </div>

                                <div class="order-item-price">
                                    Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Subtotal</span>
                        <span class="summary-value">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Shipping</span>
                        <span class="summary-value" id="shipping-cost-display">Rp 15.000</span>
                    </div>

                    <div class="summary-total">
                        <span>Total</span>
                        <span id="total-display">Rp {{ number_format($subtotal + 15000, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const subtotal = {{ $subtotal }};
            const shippingMethods = document.querySelectorAll('input[name="shipping_method"]');
            const shippingCostDisplay = document.getElementById('shipping-cost-display');
            const totalDisplay = document.getElementById('total-display');

            const shippingCosts = {
                'regular': 15000,
                'express': 30000,
                'same_day': 50000
            };

            function formatRupiah(amount) {
                return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            function updateSummary() {
                const selectedMethod = document.querySelector('input[name="shipping_method"]:checked');
                if (selectedMethod) {
                    const shippingCost = shippingCosts[selectedMethod.value];
                    const total = subtotal + shippingCost;

                    shippingCostDisplay.textContent = formatRupiah(shippingCost);
                    totalDisplay.textContent = formatRupiah(total);
                }
            }

            shippingMethods.forEach(method => {
                method.addEventListener('change', updateSummary);
            });
        });
    </script>
</x-app-layout>
