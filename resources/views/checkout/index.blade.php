<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white leading-tight font-heading">
            Checkout
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/cart/cart.css') }}">

    <div class="py-4">
        @if(session('success'))
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg relative mb-4">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg relative mb-4">
                    {{ session('error') }}
                </div>
            </div>
        @endif
    </div>

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
                                    <p class="text-sm text-gray-500 mt-1">Rp 15.000</p>
                                </div>
                            </label>

                            <label class="payment-option">
                                <input type="radio" name="shipping_method" value="express" required>
                                <div>
                                    <strong>Express (1-2 hari)</strong>
                                    <p class="text-sm text-gray-500 mt-1">Rp 30.000</p>
                                </div>
                            </label>

                            <label class="payment-option">
                                <input type="radio" name="shipping_method" value="same_day" required>
                                <div>
                                    <strong>Same Day</strong>
                                    <p class="text-sm text-gray-500 mt-1">Rp 50.000</p>
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
                                    <p class="text-sm text-gray-500 mt-1">Transfer to our bank account</p>
                                </div>
                            </label>

                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="e_wallet" required>
                                <div>
                                    <strong>E-Wallet</strong>
                                    <p class="text-sm text-gray-500 mt-1">Pay with OVO, GoPay, Dana, etc.</p>
                                </div>
                            </label>

                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="cod" required>
                                <div>
                                    <strong>Cash on Delivery (COD)</strong>
                                    <p class="text-sm text-gray-500 mt-1">Pay when the order arrives</p>
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

                    {{-- Discount display - Temporarily disabled
                    @if(session('applied_coupon'))
                    <div class="summary-row" style="color: #10b981;">
                        <span class="summary-label">Discount</span>
                        <span class="summary-value">-Rp {{ number_format(session('applied_coupon')['discount'], 0, ',', '.') }}</span>
                    </div>
                    @endif
                    --}}

                    <div class="summary-total">
                        <span>Total</span>
                        <span id="total-display">Rp {{ number_format($subtotal + 15000, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Notification function
        function showNotification(type, message) {
            const bgColor = type === 'success' ? '#d1fae5' : '#fee2e2';
            const borderColor = type === 'success' ? '#10b981' : '#991b1b';
            const iconBgColor = type === 'success' ? '#10b981' : '#991b1b';
            const textColor = type === 'success' ? '#065f46' : '#7f1d1d';
            const subTextColor = type === 'success' ? '#047857' : '#991b1b';
            const icon = type === 'success' ? '✓' : '✕';
            const title = type === 'success' ? 'Berhasil!' : 'Error!';

            const notification = document.createElement('div');
            notification.id = `${type}-notification-${Date.now()}`;
            notification.style.cssText = `position: fixed; top: 1rem; right: 1rem; z-index: 9999; background-color: ${bgColor}; border: 1px solid ${borderColor}; border-radius: 0.5rem; padding: 1rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); max-width: 400px;`;

            notification.innerHTML = `
                <div style="display: flex; align-items: start; gap: 0.75rem;">
                    <div style="flex-shrink: 0; width: 1.25rem; height: 1.25rem; background-color: ${iconBgColor}; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <span style="color: white; font-size: 0.875rem;">${icon}</span>
                    </div>
                    <div style="flex: 1;">
                        <p style="color: ${textColor}; font-weight: 600; margin: 0;">${title}</p>
                        <p style="color: ${subTextColor}; font-size: 0.875rem; margin: 0.25rem 0 0 0;">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: ${textColor}; cursor: pointer; font-size: 1.25rem; line-height: 1; padding: 0;">×</button>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.transition = 'opacity 0.3s ease-out';
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }

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

            {{-- Coupon functionality - Temporarily disabled
            const applyCouponBtn = document.getElementById('apply-coupon-btn');
            const couponCodeInput = document.getElementById('coupon-code');
            const couponMessage = document.getElementById('coupon-message');

            // Apply coupon functionality
            if (applyCouponBtn) {
                applyCouponBtn.addEventListener('click', function() {
                    const couponCode = couponCodeInput.value.trim();

                    if (!couponCode) {
                        couponMessage.textContent = 'Silakan masukkan kode kupon';
                        couponMessage.style.color = '#991b1b';
                        return;
                    }

                    // Show loading state
                    applyCouponBtn.disabled = true;
                    applyCouponBtn.textContent = 'Memproses...';
                    couponMessage.textContent = '';

                    // Send AJAX request to apply coupon
                    fetch('{{ route("checkout.apply-coupon") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ coupon_code: couponCode })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message inline
                            couponMessage.textContent = data.message;
                            couponMessage.style.color = '#047857';
                            couponMessage.style.fontWeight = '600';

                            // Show success notification
                            showNotification('success', data.message);

                            // Reload page after showing notification
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        } else {
                            couponMessage.textContent = data.message || 'Kode kupon tidak valid';
                            couponMessage.style.color = '#991b1b';
                        }
                    })
                    .catch(error => {
                        couponMessage.textContent = 'Terjadi kesalahan, silakan coba lagi';
                        couponMessage.style.color = '#991b1b';
                    })
                    .finally(() => {
                        applyCouponBtn.disabled = false;
                        applyCouponBtn.textContent = 'Terapkan';
                    });
                });

                // Allow Enter key to apply coupon
                couponCodeInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        applyCouponBtn.click();
                    }
                });
            }
            --}}
        });
    </script>
</x-app-layout>
