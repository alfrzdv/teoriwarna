<nav x-data="{ open: false, cartCount: 0 }" class="bg-dark-950/95 backdrop-blur-md border-b border-dark-800/50 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center gap-12">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->check() && auth()->user()->hasAdminAccess() ? route('admin.dashboard') : route('home') }}" class="flex items-center gap-2 group">
                        <div class="w-8 h-8 bg-gradient-to-br from-brand-500 to-purple-600 rounded-lg flex items-center justify-center shadow-glow-sm">
                            <span class="text-white font-black text-sm">T</span>
                        </div>
                        <span class="text-xl font-black font-heading text-white group-hover:text-brand-400 transition-colors">
                            teoriwarna
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden lg:flex items-center gap-1">
                    @auth
                        @if(auth()->user()->hasAdminAccess())
                            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-sm font-medium text-dark-300 hover:text-white hover:bg-dark-800/50 rounded-lg transition-all {{ request()->routeIs('admin.dashboard') ? 'text-white bg-dark-800/50' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 text-sm font-medium text-dark-300 hover:text-white hover:bg-dark-800/50 rounded-lg transition-all {{ request()->routeIs('admin.products.*') ? 'text-white bg-dark-800/50' : '' }}">
                                Products
                            </a>
                            <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 text-sm font-medium text-dark-300 hover:text-white hover:bg-dark-800/50 rounded-lg transition-all {{ request()->routeIs('admin.categories.*') ? 'text-white bg-dark-800/50' : '' }}">
                                Categories
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 text-sm font-medium text-dark-300 hover:text-white hover:bg-dark-800/50 rounded-lg transition-all {{ request()->routeIs('admin.orders.*') ? 'text-white bg-dark-800/50' : '' }}">
                                Orders
                            </a>
                        @else
                            <a href="{{ route('home') }}" class="px-4 py-2 text-sm font-medium text-dark-300 hover:text-white hover:bg-dark-800/50 rounded-lg transition-all {{ request()->routeIs('home') ? 'text-white bg-dark-800/50' : '' }}">
                                Home
                            </a>
                            <a href="{{ route('products.index') }}" class="px-4 py-2 text-sm font-medium text-dark-300 hover:text-white hover:bg-dark-800/50 rounded-lg transition-all {{ request()->routeIs('products.*') ? 'text-white bg-dark-800/50' : '' }}">
                                Shop
                            </a>
                            <a href="{{ route('orders.index') }}" class="px-4 py-2 text-sm font-medium text-dark-300 hover:text-white hover:bg-dark-800/50 rounded-lg transition-all {{ request()->routeIs('orders.*') ? 'text-white bg-dark-800/50' : '' }}">
                                My Orders
                            </a>
                        @endif
                    @else
                        <a href="{{ route('home') }}" class="px-4 py-2 text-sm font-medium text-dark-300 hover:text-white hover:bg-dark-800/50 rounded-lg transition-all {{ request()->routeIs('home') ? 'text-white bg-dark-800/50' : '' }}">
                            Home
                        </a>
                        <a href="{{ route('products.index') }}" class="px-4 py-2 text-sm font-medium text-dark-300 hover:text-white hover:bg-dark-800/50 rounded-lg transition-all {{ request()->routeIs('products.*') ? 'text-white bg-dark-800/50' : '' }}">
                            Shop
                        </a>
                    @endauth
                </div>

            </div>

            <!-- Right Side -->
            <div class="hidden lg:flex lg:items-center lg:gap-4">
                @auth
                    <!-- Cart Icon (non-admin only) -->
                    @if(!auth()->user()->hasAdminAccess())
                        <a href="{{ route('cart.index') }}" class="relative p-2 text-dark-300 hover:text-white hover:bg-dark-800/50 rounded-lg transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span x-show="cartCount > 0" x-text="cartCount" class="absolute -top-1 -right-1 bg-brand-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center"></span>
                        </a>
                    @endif

                    <!-- Profile Dropdown -->
                    <div x-data="{ profileOpen: false }" @click.away="profileOpen = false" class="relative">
                        <button @click="profileOpen = !profileOpen" class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-dark-300 hover:text-white hover:bg-dark-800/50 rounded-lg transition-all">
                            @if(Auth::user()->profile_picture)
                                <img src="{{ Auth::user()->getProfilePictureUrl() }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover border-2 border-brand-500">
                            @else
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm shadow-glow-sm">
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
                             class="absolute right-0 mt-2 w-48 bg-dark-800 border border-dark-700 rounded-lg shadow-dark overflow-hidden"
                             style="display: none;">
                            <div class="px-4 py-3 border-b border-dark-700">
                                <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-dark-400">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-dark-300 hover:bg-dark-700 hover:text-white transition-colors">
                                My Profile
                            </a>
                            @if(!auth()->user()->hasAdminAccess())
                                <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-dark-300 hover:bg-dark-700 hover:text-white transition-colors">
                                    My Orders
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-dark-700 hover:text-red-300 transition-colors">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-dark-300 hover:text-white hover:bg-dark-800/50 rounded-lg transition-all">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-500 hover:to-purple-500 rounded-lg shadow-glow-sm hover:shadow-glow transition-all">
                        Get Started
                    </a>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="flex items-center lg:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-dark-300 hover:text-white hover:bg-dark-800/50 transition-all">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden border-t border-dark-800/50">
        <div class="px-4 pt-2 pb-3 space-y-1 bg-dark-950/95 backdrop-blur-md">
            @auth
                @if(auth()->user()->hasAdminAccess())
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'text-white bg-dark-800/50' : 'text-dark-300' }} hover:text-white hover:bg-dark-800/50 rounded-lg transition-all">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="block px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.products.*') ? 'text-white bg-dark-800/50' : 'text-dark-300' }} hover:text-white hover:bg-dark-800/50 rounded-lg transition-all">
                        Products
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="block px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.categories.*') ? 'text-white bg-dark-800/50' : 'text-dark-300' }} hover:text-white hover:bg-dark-800/50 rounded-lg transition-all">
                        Categories
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="block px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.orders.*') ? 'text-white bg-dark-800/50' : 'text-dark-300' }} hover:text-white hover:bg-dark-800/50 rounded-lg transition-all">
                        Orders
                    </a>
                @else
                    <a href="{{ route('home') }}" class="block px-4 py-3 text-sm font-medium {{ request()->routeIs('home') ? 'text-white bg-dark-800/50' : 'text-dark-300' }} hover:text-white hover:bg-dark-800/50 rounded-lg transition-all">
                        Home
                    </a>
                    <a href="{{ route('products.index') }}" class="block px-4 py-3 text-sm font-medium {{ request()->routeIs('products.*') ? 'text-white bg-dark-800/50' : 'text-dark-300' }} hover:text-white hover:bg-dark-800/50 rounded-lg transition-all">
                        Shop
                    </a>
                    <a href="{{ route('cart.index') }}" class="block px-4 py-3 text-sm font-medium {{ request()->routeIs('cart.*') ? 'text-white bg-dark-800/50' : 'text-dark-300' }} hover:text-white hover:bg-dark-800/50 rounded-lg transition-all">
                        Cart
                    </a>
                    <a href="{{ route('orders.index') }}" class="block px-4 py-3 text-sm font-medium {{ request()->routeIs('orders.*') ? 'text-white bg-dark-800/50' : 'text-dark-300' }} hover:text-white hover:bg-dark-800/50 rounded-lg transition-all">
                        My Orders
                    </a>
                @endif
            @else
                <a href="{{ route('home') }}" class="block px-4 py-3 text-sm font-medium {{ request()->routeIs('home') ? 'text-white bg-dark-800/50' : 'text-dark-300' }} hover:text-white hover:bg-dark-800/50 rounded-lg transition-all">
                    Home
                </a>
                <a href="{{ route('products.index') }}" class="block px-4 py-3 text-sm font-medium {{ request()->routeIs('products.*') ? 'text-white bg-dark-800/50' : 'text-dark-300' }} hover:text-white hover:bg-dark-800/50 rounded-lg transition-all">
                    Shop
                </a>
                <a href="{{ route('cart.index') }}" class="block px-4 py-3 text-sm font-medium {{ request()->routeIs('cart.*') ? 'text-white bg-dark-800/50' : 'text-dark-300' }} hover:text-white hover:bg-dark-800/50 rounded-lg transition-all">
                    Cart
                </a>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="px-4 py-4 border-t border-dark-800/50 bg-dark-950/95">
                <div class="flex items-center mb-3">
                    @if(Auth::user()->profile_picture)
                        <img src="{{ Auth::user()->getProfilePictureUrl() }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full object-cover me-3 border-2 border-brand-500">
                    @else
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center text-white font-semibold me-3 shadow-glow-sm">
                            {{ Auth::user()->getInitials() }}
                        </div>
                    @endif
                    <div>
                        <div class="font-medium text-sm text-white">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-dark-400">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="space-y-1">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-dark-300 hover:text-white hover:bg-dark-800/50 rounded-lg transition-all">
                        My Profile
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-dark-800/50 rounded-lg transition-all">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="px-4 py-4 border-t border-dark-800/50 bg-dark-950/95 space-y-2">
                <a href="{{ route('login') }}" class="block px-4 py-2 text-center text-sm font-medium text-dark-300 hover:text-white hover:bg-dark-800/50 rounded-lg transition-all">
                    Login
                </a>
                <a href="{{ route('register') }}" class="block px-4 py-2 text-center text-sm font-medium text-white bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-500 hover:to-purple-500 rounded-lg shadow-glow-sm transition-all">
                    Get Started
                </a>
            </div>
        @endauth
    </div>
</nav>
