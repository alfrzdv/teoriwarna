<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TeoriWarna') }} - Professional Fashion E-Commerce</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Inter', sans-serif;
                background: #0f1115;
                color: #f8f9fa;
            }

            .font-heading {
                font-family: 'Poppins', 'Inter', sans-serif;
            }

            /* Custom scrollbar */
            ::-webkit-scrollbar {
                width: 10px;
            }

            ::-webkit-scrollbar-track {
                background: #1a1d23;
            }

            ::-webkit-scrollbar-thumb {
                background: #343a40;
                border-radius: 5px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: #495057;
            }
        </style>

        <!-- Additional Styles -->
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-dark-900 text-dark-50">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-gradient-to-r from-brand-600/10 via-purple-600/10 to-pink-600/10 border-b border-dark-800/50 backdrop-blur-sm">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="min-h-[calc(100vh-200px)]">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-dark-950 border-t border-dark-800/50 mt-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                        <!-- Brand -->
                        <div class="md:col-span-2">
                            <div class="text-2xl font-black font-heading text-white mb-3">
                                teoriwarna
                            </div>
                            <p class="text-dark-400 text-sm mb-4 max-w-md">
                                Your premier destination for colorful and trendy fashion. We bring style and quality together in perfect harmony.
                            </p>
                            <div class="flex gap-4">
                                <a href="#" class="w-10 h-10 bg-dark-800 hover:bg-brand-600 rounded-full flex items-center justify-center transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                                <a href="#" class="w-10 h-10 bg-dark-800 hover:bg-brand-600 rounded-full flex items-center justify-center transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                </a>
                                <a href="#" class="w-10 h-10 bg-dark-800 hover:bg-brand-600 rounded-full flex items-center justify-center transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                                </a>
                            </div>
                        </div>

                        <!-- Shop Links -->
                        <div>
                            <h3 class="text-sm font-bold font-heading text-white mb-4 uppercase tracking-wide">Shop</h3>
                            <ul class="space-y-2">
                                <li><a href="{{ route('products.index') }}" class="text-dark-400 hover:text-brand-400 transition-colors text-sm">All Products</a></li>
                                <li><a href="{{ route('cart.index') }}" class="text-dark-400 hover:text-brand-400 transition-colors text-sm">Shopping Cart</a></li>
                                @auth
                                    <li><a href="{{ route('orders.index') }}" class="text-dark-400 hover:text-brand-400 transition-colors text-sm">My Orders</a></li>
                                @endauth
                            </ul>
                        </div>

                        <!-- Account Links -->
                        <div>
                            <h3 class="text-sm font-bold font-heading text-white mb-4 uppercase tracking-wide">Account</h3>
                            <ul class="space-y-2">
                                @auth
                                    <li><a href="{{ route('profile.edit') }}" class="text-dark-400 hover:text-brand-400 transition-colors text-sm">My Profile</a></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-dark-400 hover:text-brand-400 transition-colors text-sm">Logout</button>
                                        </form>
                                    </li>
                                @else
                                    <li><a href="{{ route('login') }}" class="text-dark-400 hover:text-brand-400 transition-colors text-sm">Login</a></li>
                                    <li><a href="{{ route('register') }}" class="text-dark-400 hover:text-brand-400 transition-colors text-sm">Register</a></li>
                                @endauth
                            </ul>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-dark-800/50">
                        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                            <p class="text-dark-500 text-sm">
                                &copy; 2025 <span class="font-heading font-bold">teoriwarna.shop</span> - All rights reserved
                            </p>
                            <div class="flex gap-6 text-sm text-dark-500">
                                <a href="#" class="hover:text-brand-400 transition-colors">Privacy Policy</a>
                                <a href="#" class="hover:text-brand-400 transition-colors">Terms of Service</a>
                            </div>
                        </div>
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