<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Filters -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-4">
                            <!-- Search -->
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Cari nama atau email..."
                                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                            <!-- Role Filter -->
                            <select name="role" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="all">Semua Role</option>
                                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>

                            <!-- Status Filter -->
                            <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="all">Semua Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Banned</option>
                            </select>

                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Filter
                            </button>
                        </form>
                    </div>

                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Users Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($user->profile_picture)
                                                    <img src="{{ asset('storage/' . $user->profile_picture) }}"
                                                         alt="{{ $user->name }}"
                                                         class="w-10 h-10 rounded-full mr-3">
                                                @else
                                                    <div class="w-10 h-10 rounded-full bg-indigo-500 text-white flex items-center justify-center mr-3">
                                                        {{ $user->getInitials() }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->phone ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($user->is_banned)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Banned
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="{{ route('admin.users.show', $user) }}" class="text-indigo-600 hover:text-indigo-900">
                                                Detail
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="text-yellow-600 hover:text-yellow-900">
                                                Edit
                                            </a>
                                            @if($user->role != 'admin')
                                                @if($user->is_banned)
                                                    <form action="{{ route('admin.users.unban', $user) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-green-600 hover:text-green-900">
                                                            Unban
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('admin.users.ban', $user) }}" method="POST" class="inline"
                                                          onsubmit="return confirm('Yakin ingin ban user ini?')">
                                                        @csrf
                                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                                            Ban
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            Tidak ada user
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
