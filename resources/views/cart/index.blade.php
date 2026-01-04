<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white leading-tight font-heading">
            Shopping Cart
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($cartItems->count() > 0)
                <form id="cart-form" action="{{ route('checkout.index') }}" method="GET">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Cart Items -->
                        <div class="lg:col-span-2 space-y-4">
                            <div class="bg-gray-800 border border-gray-700 rounded-xl p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <h2 class="text-xl font-bold font-heading text-white">Your Cart ({{ $cartItems->count() }} items)</h2>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" id="select-all" class="w-4 h-4 rounded border-gray-600 bg-gray-700 text-primary-600 focus:ring-primary-500 cursor-pointer">
                                        <span class="text-sm text-gray-400">Select All</span>
                                    </label>
                                </div>

                                <div class="space-y-4">
                                    @foreach($cartItems as $item)
                                        <div class="bg-gray-900 border border-gray-700 rounded-lg p-4 hover:border-gray-600 transition-colors">
                                            <div class="flex gap-4">
                                                <div class="flex items-center">
                                                    @auth
                                                        <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" class="item-checkbox w-4 h-4 rounded border-gray-600 bg-gray-700 text-primary-600 focus:ring-primary-500 cursor-pointer">
                                                    @else
                                                        <input type="checkbox" name="selected_items[]" value="{{ $item->product->id }}" class="item-checkbox w-4 h-4 rounded border-gray-600 bg-gray-700 text-primary-600 focus:ring-primary-500 cursor-pointer">
                                                    @endauth
                                                </div>

                                                <div class="w-24 h-24 flex-shrink-0">
                                                    @if($item->product->getPrimaryImage())
                                                        <img src="{{ asset('storage/' . $item->product->getPrimaryImage()->image_path) }}"
                                                            alt="{{ $item->product->name }}"
                                                            class="w-full h-full object-cover rounded-lg">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center bg-gray-800 rounded-lg">
                                                            <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="flex-1 min-w-0">
                                                    <h3 class="font-semibold text-white mb-1">{{ $item->product->name }}</h3>
                                                    <p class="text-sm text-gray-400 mb-2">{{ $item->product->category->name }}</p>
                                                    <p class="text-sm font-medium text-primary-400">Rp {{ number_format($item->price, 0, ',', '.') }}</p>

                                                    <div class="flex items-center gap-4 mt-3">
                                                        @auth
                                                            <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center gap-2">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" name="quantity" value="{{ max(1, $item->quantity - 1) }}"
                                                                    class="w-8 h-8 flex items-center justify-center bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors">−</button>
                                                                <span class="w-12 text-center text-white font-medium">{{ $item->quantity }}</span>
                                                                <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}"
                                                                    class="w-8 h-8 flex items-center justify-center bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors">+</button>
                                                            </form>

                                                            <form action="{{ route('cart.remove', $item) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-sm text-red-400 hover:text-red-300 transition-colors" onclick="return confirm('Remove this item?')">Remove</button>
                                                            </form>
                                                        @else
                                                            <form action="{{ route('cart.update-session', $item->product->id) }}" method="POST" class="flex items-center gap-2">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" name="quantity" value="{{ max(1, $item->quantity - 1) }}"
                                                                    class="w-8 h-8 flex items-center justify-center bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors">−</button>
                                                                <span class="w-12 text-center text-white font-medium">{{ $item->quantity }}</span>
                                                                <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}"
                                                                    class="w-8 h-8 flex items-center justify-center bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors">+</button>
                                                            </form>

                                                            <form action="{{ route('cart.remove-session', $item->product->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-sm text-red-400 hover:text-red-300 transition-colors" onclick="return confirm('Remove this item?')">Remove</button>
                                                            </form>
                                                        @endauth
                                                    </div>
                                                </div>

                                                <div class="flex flex-col items-end justify-between">
                                                    <p class="text-lg font-bold text-white">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Cart Summary -->
                        <div class="lg:col-span-1">
                            <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 sticky top-24">
                                <h3 class="text-lg font-bold font-heading text-white mb-4">Order Summary</h3>

                                <div class="space-y-3 mb-4 pb-4 border-b border-gray-700">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-400">Subtotal (<span id="selected-count">0</span> items)</span>
                                        <span class="text-white font-medium" id="subtotal-display">Rp 0</span>
                                    </div>

                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-400">Shipping</span>
                                        <span class="text-white font-medium">Calculated at checkout</span>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center mb-6">
                                    <span class="text-lg font-bold text-white">Total</span>
                                    <span class="text-xl font-bold text-primary-500" id="total-display">Rp 0</span>
                                </div>

                                <button type="submit" id="checkout-btn" class="w-full px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-500 hover:to-primary-600 text-white font-medium rounded-lg shadow-sm hover:shadow transition-all mb-3">
                                    Proceed to Checkout
                                </button>

                                <a href="{{ route('products.index') }}" class="block w-full px-6 py-3 text-center bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors mb-3">
                                    Continue Shopping
                                </a>

                                <form action="{{ route('cart.clear') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-6 py-3 text-center bg-red-900/30 hover:bg-red-900/50 text-red-400 hover:text-red-300 font-medium rounded-lg border border-red-900/50 transition-all" onclick="return confirm('Clear all items from cart?')">
                                        Clear Cart
                                    </button>
                                </form>
                            </div>
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
                <div class="bg-gray-800 border border-gray-700 rounded-xl p-12">
                    <div class="text-center max-w-md mx-auto">
                        <div class="w-24 h-24 bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold font-heading text-white mb-3">Your cart is empty</h2>
                        <p class="text-gray-400 mb-6">Add some products to get started!</p>
                        <a href="{{ route('products.index') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-500 hover:to-primary-600 text-white font-medium rounded-lg shadow-sm hover:shadow transition-all">
                            Start Shopping
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
