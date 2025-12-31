<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'TeoriWarna') }} - Colorful Fashion Store</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.6;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(50px, -50px) scale(1.1); }
            66% { transform: translate(-50px, 50px) scale(0.9); }
        }

        .hero-text {
            font-size: clamp(3rem, 8vw, 8rem);
            font-weight: 900;
            line-height: 0.9;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #00f2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.4);
        }

        .card-gradient {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border: 2px solid transparent;
            background-clip: padding-box;
            position: relative;
        }

        .card-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: inherit;
            padding: 2px;
            background: linear-gradient(135deg, #667eea, #764ba2, #f093fb);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-black text-white overflow-x-hidden">
    <!-- Animated gradient background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="gradient-blob w-96 h-96 bg-gradient-to-r from-pink-500 to-violet-500 top-10 left-10" style="animation-delay: 0s;"></div>
        <div class="gradient-blob w-80 h-80 bg-gradient-to-r from-cyan-500 to-blue-500 top-1/3 right-20" style="animation-delay: 5s;"></div>
        <div class="gradient-blob w-72 h-72 bg-gradient-to-r from-yellow-400 to-orange-500 bottom-20 left-1/4" style="animation-delay: 10s;"></div>
        <div class="gradient-blob w-64 h-64 bg-gradient-to-r from-green-400 to-emerald-500 bottom-10 right-1/3" style="animation-delay: 15s;"></div>
    </div>

    <!-- Navigation -->
    <nav class="relative z-10 container mx-auto px-6 py-8">
        <div class="flex items-center justify-between">
            <div class="text-2xl font-black">
                <span class="bg-gradient-to-r from-pink-500 via-purple-500 to-cyan-500 bg-clip-text text-transparent">
                    teoriwarna.shop
                </span>
            </div>

            <div class="flex items-center gap-4">
                @auth
                    @if(auth()->user()->hasAdminAccess())
                        <a href="{{ url('/admin') }}" class="px-6 py-3 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 hover:bg-white/20 transition-all">
                            Admin Panel
                        </a>
                    @endif
                    <a href="{{ route('catalog.index') }}" class="px-6 py-3 rounded-full btn-gradient text-white font-semibold">
                        Shop Now
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-3 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 hover:bg-white/20 transition-all">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="px-6 py-3 rounded-full btn-gradient text-white font-semibold">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative z-10 container mx-auto px-6 pt-20 pb-32">
        <div class="max-w-6xl mx-auto text-center">
            <h1 class="hero-text mb-8">
                teoriwarna.shop
            </h1>
            <p class="text-xl md:text-2xl text-gray-300 mb-12 max-w-3xl mx-auto">
                al farizd syawaludin | 607022400043
            </p>
            <div class="flex flex-wrap gap-6 justify-center items-center mb-16">
                <a href="{{ route('catalog.index') }}" class="px-8 py-4 rounded-full btn-gradient text-white font-bold text-lg">
                    Explore Products
                </a>
                @guest
                <a href="{{ route('register') }}" class="px-8 py-4 rounded-full bg-white/10 backdrop-blur-sm border-2 border-white/30 hover:bg-white/20 transition-all font-bold text-lg">
                    Get Started
                </a>
                @endguest
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="relative z-10 container mx-auto px-6 pb-32">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-4xl md:text-6xl font-black text-center mb-4">
                <span class="text-yellow-100">AUTHENTICATION</span>
            </h2>
            <h3 class="text-4xl md:text-6xl font-black text-center mb-16">
                <span class="bg-gradient-to-r from-yellow-400 via-pink-500 to-purple-600 bg-clip-text text-transparent">
                    User Access Flow
                </span>
            </h3>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Guest Access -->
                <div class="card-gradient rounded-3xl p-8 backdrop-blur-sm bg-white/5">
                    <div class="text-2xl font-black mb-4 text-yellow-100">Guest Access</div>
                    <p class="text-gray-400 mb-6">Jelajahi produk tanpa registrasi. Tidak perlu login untuk melihat katalog.</p>
                </div>

                <!-- User Login -->
                <div class="card-gradient rounded-3xl p-8 backdrop-blur-sm bg-white/5">
                    <div class="text-2xl font-black mb-4 text-pink-400">User Login</div>
                    <p class="text-gray-400 mb-6">Validasi kredensial dengan routing dashboard berbasis role ke interface User atau Admin.</p>
                </div>

                <!-- Registration -->
                <div class="card-gradient rounded-3xl p-8 backdrop-blur-sm bg-white/5">
                    <div class="text-2xl font-black mb-4 text-cyan-400">Registration</div>
                    <p class="text-gray-400 mb-6">Pembuatan akun baru dengan setup profil user otomatis dan assignment role.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Admin Functions Section -->
    <section class="relative z-10 container mx-auto px-6 pb-32">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-4xl md:text-6xl font-black text-center mb-4">
                <span class="text-green-300">ADMIN FUNCTION</span>
            </h2>
            <h3 class="text-4xl md:text-6xl font-black text-center mb-16">
                <span class="bg-gradient-to-r from-green-400 via-emerald-500 to-teal-600 bg-clip-text text-transparent">
                    Product Management
                </span>
            </h3>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="card-gradient rounded-2xl p-6 backdrop-blur-sm bg-gradient-to-br from-blue-500/10 to-blue-600/10">
                    <div class="text-4xl font-black mb-4 text-blue-400">
                        Add & Edit Products
                    </div>
                    <p class="text-gray-300 text-sm">Buat listing baru dengan detail, spesifikasi, gambar, harga, dan level stok</p>
                </div>

                <div class="card-gradient rounded-2xl p-6 backdrop-blur-sm bg-gradient-to-br from-yellow-500/10 to-yellow-600/10">
                    <div class="text-4xl font-black mb-4 text-yellow-400">
                        Category Management
                    </div>
                    <p class="text-gray-300 text-sm">Organisir produk ke dalam kategori hierarki untuk navigasi mudah</p>
                </div>

                <div class="card-gradient rounded-2xl p-6 backdrop-blur-sm bg-gradient-to-br from-orange-500/10 to-orange-600/10">
                    <div class="text-4xl font-black mb-4 text-orange-400">
                        Stock Management
                    </div>
                    <p class="text-gray-300 text-sm">Monitor level inventori, alert stok rendah, dan update bulk</p>
                </div>

                <div class="card-gradient rounded-2xl p-6 backdrop-blur-sm bg-gradient-to-br from-red-500/10 to-red-600/10">
                    <div class="text-4xl font-black mb-4 text-red-400">
                        Delete Products
                    </div>
                    <p class="text-gray-300 text-sm">Monitor level inventori, alert stok rendah, dan update bulk</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Payment Section -->
    <section class="relative z-10 container mx-auto px-6 pb-32">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-4xl md:text-6xl font-black text-center mb-4">
                <span class="text-red-300">TRANSACTION</span>
            </h2>
            <h3 class="text-4xl md:text-6xl font-black text-center mb-16">
                <span class="bg-gradient-to-r from-red-400 via-pink-500 to-purple-600 bg-clip-text text-transparent">
                    Payment Processing
                </span>
            </h3>

            <div class="grid md:grid-cols-3 gap-8 mb-12">
                <div class="text-center">
                    <div class="text-xl font-bold mb-2 text-red-400">Bank Transfer</div>
                </div>

                <div class="text-center">
                    <div class="text-xl font-bold mb-2 text-green-400">E-Wallet</div>
                </div>

                <div class="text-center">
                    <div class="text-xl font-bold mb-2 text-yellow-400">Cash on Delivery</div>
                </div>
            </div>

            <div class="card-gradient rounded-3xl p-12 backdrop-blur-sm bg-white/5 text-center">
                <div class="text-6xl font-black mb-6 text-blue-400">
                    Automatic Order Creation
                </div>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Sistem generate record pesanan dengan ID unik. Notifikasi otomatis terkirim. Status diupdate ke processing.
                </p>
            </div>
        </div>
    </section>

    <!-- Footer CTA -->
    <section class="relative z-10 container mx-auto px-6 pb-32">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-5xl md:text-7xl font-black mb-8">
                <span class="bg-gradient-to-r from-pink-500 via-purple-500 to-cyan-500 bg-clip-text text-transparent">
                    Ready to Shop?
                </span>
            </h2>
            <p class="text-xl text-gray-300 mb-12">
                Explore our colorful collection and find your perfect style
            </p>
            <a href="{{ route('catalog.index') }}" class="inline-block px-12 py-5 rounded-full btn-gradient text-white font-bold text-xl">
                Start Shopping
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="relative z-10 border-t border-white/10 py-12">
        <div class="container mx-auto px-6 text-center text-gray-400">
            <p>&copy; 2025 teoriwarna.shop - Al Farizd Syawaludin (607022400043)</p>
        </div>
    </footer>
</body>
</html>
