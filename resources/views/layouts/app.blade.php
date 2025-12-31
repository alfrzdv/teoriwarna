<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Inter', sans-serif;
                background: #1a1a1a;
                color: white;
            }
        </style>

        <!-- Additional Styles -->
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-[#1a1a1a] text-white">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-gradient-to-r from-purple-900/30 to-pink-900/30 backdrop-blur-sm">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-black border-t border-[#222] py-6 mt-12">
                <div class="max-w-7xl mx-auto px-2 sm:px-4">
                    <div class="flex flex-wrap justify-between items-start gap-8">
                        <!-- Brand -->
                        <div>
                            <div class="text-base font-black text-white uppercase mb-2" style="letter-spacing: -0.5px;">
                                teoriwarna
                            </div>
                            <p class="text-[8px] text-gray-500 uppercase" style="letter-spacing: -0.4px;">
                                Colorful Fashion Store
                            </p>
                        </div>

                        <!-- Links -->
                        <div class="flex gap-12">
                            <div>
                                <h3 class="text-[8px] font-black uppercase text-white mb-2" style="letter-spacing: -0.4px;">Shop</h3>
                                <div class="space-y-1">
                                    <a href="{{ route('products.index') }}" class="block text-[8px] uppercase text-gray-500 hover:text-white transition-colors" style="letter-spacing: -0.4px;">Catalog</a>
                                    <a href="{{ route('cart.index') }}" class="block text-[8px] uppercase text-gray-500 hover:text-white transition-colors" style="letter-spacing: -0.4px;">Cart</a>
                                    @auth
                                        <a href="{{ route('orders.index') }}" class="block text-[8px] uppercase text-gray-500 hover:text-white transition-colors" style="letter-spacing: -0.4px;">Orders</a>
                                    @endauth
                                </div>
                            </div>

                            <div>
                                <h3 class="text-[8px] font-black uppercase text-white mb-2" style="letter-spacing: -0.4px;">Account</h3>
                                <div class="space-y-1">
                                    @auth
                                        <a href="{{ route('profile.edit') }}" class="block text-[8px] uppercase text-gray-500 hover:text-white transition-colors" style="letter-spacing: -0.4px;">Profile</a>
                                        <form method="POST" action="{{ route('logout') }}" class="inline">
                                            @csrf
                                            <button type="submit" class="block text-[8px] uppercase text-gray-500 hover:text-white transition-colors text-left" style="letter-spacing: -0.4px;">Logout</button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="block text-[8px] uppercase text-gray-500 hover:text-white transition-colors" style="letter-spacing: -0.4px;">Login</a>
                                        <a href="{{ route('register') }}" class="block text-[8px] uppercase text-gray-500 hover:text-white transition-colors" style="letter-spacing: -0.4px;">Register</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-[#222]">
                        <p class="text-[8px] text-gray-600 uppercase" style="letter-spacing: -0.4px;">
                            &copy; 2025 teoriwarna.shop - All rights reserved
                        </p>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Toast Notification -->
        <x-toast />

        <!-- Additional Scripts -->
        @stack('scripts')
    </body>
</html>