<nav x-data="{ open: false }" class="bg-black border-b border-[#222]">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-2 sm:px-4">
        <div class="flex justify-between items-center h-12">
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->check() && auth()->user()->hasAdminAccess() ? route('admin.dashboard') : route('products.index') }}">
                        <span class="text-base font-black text-white uppercase" style="letter-spacing: -0.5px;">
                            teoriwarna
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex items-center gap-6">
                    @auth
                        @if(auth()->user()->hasAdminAccess())
                            <a href="{{ route('admin.dashboard') }}" class="text-[8px] font-black uppercase text-gray-400 hover:text-white transition-colors" style="letter-spacing: -0.4px;">
                                Dashboard
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="text-[8px] font-black uppercase text-gray-400 hover:text-white transition-colors" style="letter-spacing: -0.4px;">
                                Products
                            </a>
                            <a href="{{ route('admin.categories.index') }}" class="text-[8px] font-black uppercase text-gray-400 hover:text-white transition-colors" style="letter-spacing: -0.4px;">
                                Categories
                            </a>
                        @else
                            <a href="{{ route('products.index') }}" class="text-[8px] font-black uppercase text-gray-400 hover:text-white transition-colors" style="letter-spacing: -0.4px;">
                                Catalog
                            </a>
                            <a href="{{ route('cart.index') }}" class="text-[8px] font-black uppercase text-gray-400 hover:text-white transition-colors" style="letter-spacing: -0.4px;">
                                Cart
                            </a>
                            <a href="{{ route('orders.index') }}" class="text-[8px] font-black uppercase text-gray-400 hover:text-white transition-colors" style="letter-spacing: -0.4px;">
                                Orders
                            </a>
                        @endif
                    @else
                        <a href="{{ route('products.index') }}" class="text-[8px] font-black uppercase text-gray-400 hover:text-white transition-colors" style="letter-spacing: -0.4px;">
                            Catalog
                        </a>
                        <a href="{{ route('cart.index') }}" class="text-[8px] font-black uppercase text-gray-400 hover:text-white transition-colors" style="letter-spacing: -0.4px;">
                            Cart
                        </a>
                    @endauth
                </div>

            </div>

            <!-- Settings Dropdown / Login Register -->
            <div class="hidden sm:flex sm:items-center gap-4">
                @auth
                    <a href="{{ route('profile.edit') }}" class="text-[8px] font-black uppercase text-gray-400 hover:text-white transition-colors" style="letter-spacing: -0.4px;">
                        Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-[8px] font-black uppercase text-gray-400 hover:text-white transition-colors" style="letter-spacing: -0.4px;">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-[8px] font-black uppercase text-gray-400 hover:text-white transition-colors" style="letter-spacing: -0.4px;">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="text-[8px] font-black uppercase text-white bg-white/10 hover:bg-white/20 px-3 py-1.5 transition-colors" style="letter-spacing: -0.4px;">
                        Register
                    </a>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-gray-200 hover:bg-white/10 focus:outline-none focus:bg-white/10 focus:text-gray-200 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                @if(auth()->user()->hasAdminAccess())
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
                        {{ __('Products') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                        {{ __('Categories') }}
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                        {{ __('Products') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">
                        {{ __('Cart') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                        {{ __('My Orders') }}
                    </x-responsive-nav-link>
                @endif
            @else
                <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                    {{ __('Products') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">
                    {{ __('Cart') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('login')">
                    {{ __('My Orders') }}
                </x-responsive-nav-link>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-white/10">
                <div class="px-4 flex items-center">
                    <!-- Profile Picture -->
                    @if(Auth::user()->profile_picture)
                        <img src="{{ Auth::user()->getProfilePictureUrl() }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full object-cover me-3 border-2 border-purple-500">
                    @else
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold me-3">
                            {{ Auth::user()->getInitials() }}
                        </div>
                    @endif
                    <div>
                        <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-300">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-1 border-t border-white/10">
                <div class="px-4 space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Log in') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                </div>
            </div>
        @endauth
    </div>
</nav>
