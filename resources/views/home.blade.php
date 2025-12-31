<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'TeoriWarna') }} - Premium Fashion E-Commerce</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #0f1115;
        }

        .font-heading {
            font-family: 'Poppins', 'Inter', sans-serif;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-size: 200% 200%;
            animation: gradient 15s ease infinite;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .glow-text {
            text-shadow: 0 0 40px rgba(168, 85, 247, 0.5);
        }
    </style>
</head>
<body class="bg-dark-900 text-white overflow-x-hidden">
    <!-- Navigation -->
    <nav class="bg-dark-950/95 backdrop-blur-md border-b border-dark-800/50 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-brand-500 to-purple-600 rounded-lg flex items-center justify-center shadow-glow-sm">
                        <span class="text-white font-black text-sm">T</span>
                    </div>
                    <span class="text-xl font-black font-heading text-white">
                        teoriwarna
                    </span>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        @if(auth()->user()->hasAdminAccess())
                            <a href="{{ url('/admin') }}" class="px-4 py-2 bg-dark-800 hover:bg-dark-700 text-white font-medium rounded-lg transition-all">
                                Admin Panel
                            </a>
                        @endif
                        <a href="{{ route('products.index') }}" class="px-6 py-2 bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-500 hover:to-purple-500 text-white font-medium rounded-lg shadow-glow-sm hover:shadow-glow transition-all">
                            Shop Now
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-dark-300 hover:text-white hover:bg-dark-800/50 rounded-lg transition-all">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="px-6 py-2 bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-500 hover:to-purple-500 text-white font-medium rounded-lg shadow-glow-sm hover:shadow-glow transition-all">
                            Get Started
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative overflow-hidden">
        <!-- Background Gradient -->
        <div class="absolute inset-0 hero-gradient opacity-10"></div>

        <!-- Content -->
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-32 lg:pt-32 lg:pb-40">
            <div class="text-center">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-brand-600/10 border border-brand-500/20 rounded-full mb-8">
                    <span class="w-2 h-2 bg-brand-500 rounded-full animate-pulse"></span>
                    <span class="text-brand-400 text-sm font-medium">Premium Fashion Collection 2025</span>
                </div>

                <!-- Main Heading -->
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-black font-heading mb-6 glow-text">
                    <span class="bg-gradient-to-r from-brand-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
                        teoriwarna
                    </span>
                </h1>

                <p class="text-xl md:text-2xl text-dark-300 mb-4 max-w-3xl mx-auto">
                    Discover Your True Colors
                </p>

                <p class="text-base md:text-lg text-dark-400 mb-12 max-w-2xl mx-auto">
                    Premium fashion e-commerce bringing you the latest trends in style and quality. Express yourself through color.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-500 hover:to-purple-500 text-white font-bold text-lg rounded-lg shadow-glow hover:shadow-glow-sm transition-all transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Shop Collection
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-dark-800 hover:bg-dark-700 text-white font-semibold text-lg rounded-lg border border-dark-700 hover:border-brand-500/50 transition-all">
                            Get Started Free
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="bg-dark-950 border-y border-dark-800/50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-black font-heading text-white mb-4">
                    Why Choose TeoriWarna
                </h2>
                <p class="text-dark-400 max-w-2xl mx-auto">
                    Experience seamless shopping with powerful features designed for you
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-dark-800/30 border border-dark-700/50 rounded-xl p-8 hover:border-brand-500/50 transition-all group">
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-lg flex items-center justify-center mb-6 group-hover:shadow-glow-sm transition-all">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold font-heading text-white mb-3">Browse Freely</h3>
                    <p class="text-dark-400">Explore our entire product catalog without needing to create an account. Shop at your own pace.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-dark-800/30 border border-dark-700/50 rounded-xl p-8 hover:border-brand-500/50 transition-all group">
                    <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-purple-600 rounded-lg flex items-center justify-center mb-6 group-hover:shadow-glow-sm transition-all">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold font-heading text-white mb-3">Personalized Experience</h3>
                    <p class="text-dark-400">Create an account to track orders, save favorites, and get personalized recommendations tailored to you.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-dark-800/30 border border-dark-700/50 rounded-xl p-8 hover:border-brand-500/50 transition-all group">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-600 rounded-lg flex items-center justify-center mb-6 group-hover:shadow-glow-sm transition-all">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold font-heading text-white mb-3">Secure Checkout</h3>
                    <p class="text-dark-400">Shop with confidence using our secure payment system and comprehensive order management.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="relative overflow-hidden py-20">
        <div class="absolute inset-0 bg-gradient-to-r from-brand-600/20 to-purple-600/20"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-black font-heading text-white mb-6">
                Ready to Transform Your Style?
            </h2>
            <p class="text-lg text-dark-300 mb-10 max-w-2xl mx-auto">
                Join thousands of satisfied customers and discover the perfect blend of quality and fashion
            </p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-10 py-4 bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-500 hover:to-purple-500 text-white font-bold text-lg rounded-lg shadow-glow hover:shadow-glow-sm transition-all transform hover:scale-105">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                Start Shopping Now
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark-950 border-t border-dark-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- Brand -->
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-brand-500 to-purple-600 rounded-lg flex items-center justify-center shadow-glow-sm">
                            <span class="text-white font-black text-sm">T</span>
                        </div>
                        <span class="text-2xl font-black font-heading text-white">teoriwarna</span>
                    </div>
                    <p class="text-dark-400 text-sm mb-4 max-w-md">
                        Your premier destination for art supply.
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
                    </ul>
                </div>

                <!-- Account Links -->
                <div>
                    <h3 class="text-sm font-bold font-heading text-white mb-4 uppercase tracking-wide">Account</h3>
                    <ul class="space-y-2">
                        @auth
                            <li><a href="{{ route('profile.edit') }}" class="text-dark-400 hover:text-brand-400 transition-colors text-sm">My Profile</a></li>
                            <li><a href="{{ route('orders.index') }}" class="text-dark-400 hover:text-brand-400 transition-colors text-sm">My Orders</a></li>
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
</body>
</html>
