<nav x-data="{
    open: false,
    cartCount: 0
}" x-init="
    // Fetch cart count immediately on page load
    fetch('{{ route('cart.count') }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        cartCount = data.count || 0;
        console.log('Cart count loaded:', cartCount);
    })
    .catch(error => {
        console.error('Failed to fetch cart count:', error);
    });

    // Update cart count when triggered
    window.addEventListener('cart-updated', (e) => {
        cartCount = e.detail.count;
        console.log('Cart updated to:', cartCount);
    });
" class="bg-gray-900 border-b border-gray-800 sticky top-0 z-50 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center gap-12">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->check() && auth()->user()->hasAdminAccess() ? '/admin' : route('home') }}" class="flex items-center gap-2 group">
                        <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-black text-sm">T</span>
                        </div>
                        <span class="text-xl font-bold font-heading text-white transition-colors">
                            <span class="group-hover:text-primary-500 transition-colors">teoriwarna</span><span class="text-primary-500">.shop</span>
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden lg:flex items-center gap-1">
                    @auth
                        @if(auth()->user()->hasAdminAccess())
                            <a href="/admin" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-all {{ request()->is('admin') || request()->is('admin/dashboard') ? 'text-white bg-gray-800' : '' }}">
                                Dashboard
                            </a>
                            <a href="/admin/products" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-all {{ request()->is('admin/products*') ? 'text-white bg-gray-800' : '' }}">
                                Produk
                            </a>
                            <a href="/admin/categories" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-all {{ request()->is('admin/categories*') ? 'text-white bg-gray-800' : '' }}">
                                Kategori
                            </a>
                            <a href="/admin/orders" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-all {{ request()->is('admin/orders*') ? 'text-white bg-gray-800' : '' }}">
                                Pesanan
                            </a>
                        @else
                            <a href="{{ route('products.index') }}" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-all {{ request()->routeIs('products.*') ? 'text-white bg-gray-800' : '' }}">
                                Belanja
                            </a>
                            <a href="{{ route('orders.index') }}" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-all {{ request()->routeIs('orders.*') ? 'text-white bg-gray-800' : '' }}">
                                Pesanan Saya
                            </a>
                        @endif
                    @else
                        <a href="{{ route('products.index') }}" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-all {{ request()->routeIs('products.*') ? 'text-white bg-gray-800' : '' }}">
                            Belanja
                        </a>
                    @endauth
                </div>

            </div>

            <!-- Right Side -->
            <div class="hidden lg:flex lg:items-center lg:gap-4">
                <!-- Cart Icon (for guests and non-admin users) -->
                @auth
                    @if(!auth()->user()->hasAdminAccess())
                        <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span x-text="cartCount" class="absolute -top-1 -right-1 bg-brand-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center"></span>
                        </a>
                    @endif
                @else
                    <!-- Cart Icon for guests -->
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span x-text="cartCount" class="absolute -top-1 -right-1 bg-brand-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center"></span>
                    </a>
                @endauth

                @auth
                    <!-- Profile Dropdown -->
                    <div x-data="{ profileOpen: false }" @click.away="profileOpen = false" class="relative">
                        <button @click="profileOpen = !profileOpen" class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-all">
                            @if(Auth::user()->profile_picture)
                                <img src="{{ Auth::user()->getProfilePictureUrl() }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover border-2 border-primary-500">
                            @else
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white font-semibold text-sm">
                                    {{ Auth::user()->getInitials() }}
                                </div>
                            @endif
                            <span class="hidden xl:block">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="profileOpen"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-gray-800 border border-gray-700 rounded-lg shadow-lg overflow-hidden"
                             style="display: none;">
                            <div class="px-4 py-3 border-b border-gray-700">
                                <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-400 hover:bg-gray-700 hover:text-white transition-colors">
                                Profil Saya
                            </a>
                            @if(!auth()->user()->hasAdminAccess())
                                <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-gray-400 hover:bg-gray-700 hover:text-white transition-colors">
                                    Pesanan Saya
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-700 hover:text-red-300 transition-colors">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-all">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-500 hover:to-primary-600 rounded-lg shadow-sm hover:shadow transition-all">
                        Daftar
                    </a>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="flex items-center lg:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition-all">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden border-t border-gray-800">
        <div class="px-4 pt-2 pb-3 space-y-1 bg-gray-900">
            @auth
                @if(auth()->user()->hasAdminAccess())
                    <a href="/admin" class="block px-4 py-3 text-sm font-medium {{ request()->is('admin') || request()->is('admin/dashboard') ? 'text-white bg-gray-800' : 'text-gray-400' }} hover:text-white hover:bg-gray-800 rounded-lg transition-all">
                        Dashboard
                    </a>
                    <a href="/admin/products" class="block px-4 py-3 text-sm font-medium {{ request()->is('admin/products*') ? 'text-white bg-gray-800' : 'text-gray-400' }} hover:text-white hover:bg-gray-800 rounded-lg transition-all">
                        Produk
                    </a>
                    <a href="/admin/categories" class="block px-4 py-3 text-sm font-medium {{ request()->is('admin/categories*') ? 'text-white bg-gray-800' : 'text-gray-400' }} hover:text-white hover:bg-gray-800 rounded-lg transition-all">
                        Kategori
                    </a>
                    <a href="/admin/orders" class="block px-4 py-3 text-sm font-medium {{ request()->is('admin/orders*') ? 'text-white bg-gray-800' : 'text-gray-400' }} hover:text-white hover:bg-gray-800 rounded-lg transition-all">
                        Pesanan
                    </a>
                @else
                    <a href="{{ route('products.index') }}" class="block px-4 py-3 text-sm font-medium {{ request()->routeIs('products.*') ? 'text-white bg-gray-800' : 'text-gray-400' }} hover:text-white hover:bg-gray-800 rounded-lg transition-all">
                        Belanja
                    </a>
                    <a href="{{ route('cart.index') }}" class="block px-4 py-3 text-sm font-medium {{ request()->routeIs('cart.*') ? 'text-white bg-gray-800' : 'text-gray-400' }} hover:text-white hover:bg-gray-800 rounded-lg transition-all">
                        Keranjang
                    </a>
                    <a href="{{ route('orders.index') }}" class="block px-4 py-3 text-sm font-medium {{ request()->routeIs('orders.*') ? 'text-white bg-gray-800' : 'text-gray-400' }} hover:text-white hover:bg-gray-800 rounded-lg transition-all">
                        Pesanan Saya
                    </a>
                @endif
            @else
                <a href="{{ route('products.index') }}" class="block px-4 py-3 text-sm font-medium {{ request()->routeIs('products.*') ? 'text-white bg-gray-800' : 'text-gray-400' }} hover:text-white hover:bg-gray-800 rounded-lg transition-all">
                    Belanja
                </a>
                <a href="{{ route('cart.index') }}" class="block px-4 py-3 text-sm font-medium {{ request()->routeIs('cart.*') ? 'text-white bg-gray-800' : 'text-gray-400' }} hover:text-white hover:bg-gray-800 rounded-lg transition-all">
                    Keranjang
                </a>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="px-4 py-4 border-t border-gray-800 bg-gray-900">
                <div class="flex items-center mb-3">
                    @if(Auth::user()->profile_picture)
                        <img src="{{ Auth::user()->getProfilePictureUrl() }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full object-cover me-3 border-2 border-primary-500">
                    @else
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white font-semibold me-3">
                            {{ Auth::user()->getInitials() }}
                        </div>
                    @endif
                    <div>
                        <div class="font-medium text-sm text-white">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-400">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="space-y-1">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-all">
                        Profil Saya
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-gray-800 rounded-lg transition-all">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="px-4 py-4 border-t border-gray-800 bg-gray-900 space-y-2">
                <a href="{{ route('login') }}" class="block px-4 py-2 text-center text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-all">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="block px-4 py-2 text-center text-sm font-medium text-white bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-500 hover:to-primary-600 rounded-lg shadow-sm transition-all">
                    Daftar
                </a>
            </div>
        @endauth
    </div>
</nav>
