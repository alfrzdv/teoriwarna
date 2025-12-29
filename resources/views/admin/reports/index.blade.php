<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Laporan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Sales Report -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Laporan Penjualan</h3>
                                <p class="text-sm text-gray-600">Lihat laporan penjualan dan revenue</p>
                            </div>
                            <div class="text-4xl">📊</div>
                        </div>
                        <a href="{{ route('admin.reports.sales') }}"
                           class="mt-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Lihat Laporan
                        </a>
                    </div>
                </div>

                <!-- Product Report -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Laporan Produk</h3>
                                <p class="text-sm text-gray-600">Analisis performa produk dan stok</p>
                            </div>
                            <div class="text-4xl">📦</div>
                        </div>
                        <a href="{{ route('admin.reports.products') }}"
                           class="mt-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Lihat Laporan
                        </a>
                    </div>
                </div>

                <!-- Transaction Report -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Laporan Transaksi</h3>
                                <p class="text-sm text-gray-600">Detail semua transaksi dan pembayaran</p>
                            </div>
                            <div class="text-4xl">💳</div>
                        </div>
                        <a href="{{ route('admin.reports.transactions') }}"
                           class="mt-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Lihat Laporan
                        </a>
                    </div>
                </div>

                <!-- User Report -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Laporan User</h3>
                                <p class="text-sm text-gray-600">Aktivitas dan spending user</p>
                            </div>
                            <div class="text-4xl">👥</div>
                        </div>
                        <a href="{{ route('admin.reports.users') }}"
                           class="mt-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Lihat Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
