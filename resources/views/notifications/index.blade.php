<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Notifikasi') }}
            </h2>
            @if($notifications->where('read_at', null)->count() > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-900">
                        Tandai Semua Dibaca
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="space-y-3">
                @forelse ($notifications as $notification)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg {{ $notification->read_at ? 'opacity-75' : 'border-l-4 border-indigo-500' }}">
                        <div class="p-4">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="font-semibold">{{ $notification->title }}</h3>
                                        @if(!$notification->read_at)
                                            <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">Baru</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600">{{ $notification->message }}</p>
                                    <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>

                                <div class="flex gap-2">
                                    @if(!$notification->read_at)
                                        <form action="{{ route('notifications.read', $notification) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-900">
                                                Tandai Dibaca
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('notifications.destroy', $notification) }}" method="POST"
                                          onsubmit="return confirm('Hapus notifikasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:text-red-900">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-center text-gray-500">
                            Tidak ada notifikasi
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
