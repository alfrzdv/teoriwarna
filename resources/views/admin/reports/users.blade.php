<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan User</h2>
            <a href="{{ route('admin.reports.index') }}" class="text-indigo-600 hover:text-indigo-800">← Kembali</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-white p-6 shadow-sm rounded-lg">
                    <div class="text-sm text-gray-500">Total Users</div>
                    <div class="text-2xl font-bold">{{ $totalUsers }}</div>
                </div>
                <div class="bg-white p-6 shadow-sm rounded-lg">
                    <div class="text-sm text-gray-500">Active Users (30d)</div>
                    <div class="text-2xl font-bold">{{ $activeUsers }}</div>
                </div>
                <div class="bg-white p-6 shadow-sm rounded-lg">
                    <div class="text-sm text-gray-500">Banned Users</div>
                    <div class="text-2xl font-bold text-red-600">{{ $bannedUsers }}</div>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Top Customers</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Orders</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Completed</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Spent</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Order</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($users as $item)
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium">{{ $item['user']->name }}</td>
                                <td class="px-4 py-3 text-sm">{{ $item['user']->email }}</td>
                                <td class="px-4 py-3 text-sm">{{ $item['total_orders'] }}</td>
                                <td class="px-4 py-3 text-sm">{{ $item['completed_orders'] }}</td>
                                <td class="px-4 py-3 text-sm font-bold">Rp {{ number_format($item['total_spent'], 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm">{{ $item['last_order'] ? $item['last_order']->created_at->format('d M Y') : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
