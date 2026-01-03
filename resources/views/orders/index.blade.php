<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white leading-tight font-heading">
            Pesanan Saya
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-600/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg relative mb-6 backdrop-blur-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-8">
                <x-section-header title="Riwayat Pesanan" subtitle="Pesanan Saya">
                    <p class="text-dark-400 text-sm">
                        Lacak dan kelola pesanan Anda
                    </p>
                </x-section-header>
            </div>

            @if($orders->count() > 0)
                <div class="space-y-4">
                    @foreach($orders as $order)
                        <div class="bg-dark-800/50 backdrop-blur-sm border border-dark-700/50 rounded-xl overflow-hidden hover:border-brand-500/30 transition-all">
                            <div class="p-6">
                                <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-4">
                                    <div>
                                        <h3 class="text-lg font-heading font-bold text-white">Order #{{ $order->order_number }}</h3>
                                        <p class="text-sm text-dark-400">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                    </div>

                                    <span class="px-4 py-2 text-sm font-semibold rounded-full inline-block
                                        {{ $order->status == 'pending' ? 'bg-yellow-600/20 text-yellow-400 border border-yellow-500/30' : '' }}
                                        {{ $order->status == 'paid' ? 'bg-teal-600/20 text-teal-400 border border-teal-500/30' : '' }}
                                        {{ $order->status == 'processing' ? 'bg-blue-600/20 text-blue-400 border border-blue-500/30' : '' }}
                                        {{ $order->status == 'shipped' ? 'bg-purple-600/20 text-purple-400 border border-purple-500/30' : '' }}
                                        {{ $order->status == 'completed' ? 'bg-green-600/20 text-green-400 border border-green-500/30' : '' }}
                                        {{ $order->status == 'cancelled' ? 'bg-red-600/20 text-red-400 border border-red-500/30' : '' }}">
                                        @php
                                            $statusLabels = [
                                                'pending' => 'Menunggu',
                                                'paid' => 'Dibayar',
                                                'processing' => 'Diproses',
                                                'shipped' => 'Dikirim',
                                                'completed' => 'Selesai',
                                                'cancelled' => 'Dibatalkan'
                                            ];
                                        @endphp
                                        {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                    </span>
                                </div>

                                <div class="border-t border-dark-700/50 pt-4">
                                    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                                        <div>
                                            <p class="text-sm text-dark-400">{{ $order->order_items->count() }} item</p>
                                            <p class="text-xl font-bold font-heading text-brand-400">
                                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                            </p>
                                        </div>

                                        <div class="flex gap-2">
                                            <a href="{{ route('orders.show', $order) }}"
                                                class="px-6 py-2.5 bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-500 hover:to-purple-500 text-white text-sm font-medium rounded-lg shadow-glow-sm hover:shadow-glow transition-all">
                                                Lihat Detail
                                            </a>

                                            @if($order->status == 'pending')
                                                <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="px-6 py-2.5 bg-dark-700 hover:bg-red-600/20 text-red-400 border border-red-500/30 hover:border-red-500/50 text-sm font-medium rounded-lg transition-all"
                                                        onclick="return confirm('Batalkan pesanan ini?')">
                                                        Batalkan Pesanan
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="bg-dark-800/50 backdrop-blur-sm border border-dark-700/50 rounded-xl overflow-hidden">
                    <div class="p-12 text-center">
                        <div class="w-20 h-20 bg-dark-700/50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-dark-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold font-heading text-white mb-3">Belum Ada Pesanan</h3>
                        <p class="text-dark-400 mb-6">Anda belum membuat pesanan apapun. Mulai belanja sekarang!</p>
                        <a href="{{ route('products.index') }}"
                            class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-500 hover:to-purple-500 text-white font-medium rounded-lg shadow-glow-sm hover:shadow-glow transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Mulai Belanja
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
