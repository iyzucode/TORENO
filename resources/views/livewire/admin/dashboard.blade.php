<x-slot name="header">
    <h2 class="font-semibold text-xl text-toreno-brown leading-tight">
        {{ __('Admin Dashboard') }}
    </h2>
</x-slot>

<div class="py-12 bg-toreno-light min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Orders -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-toreno-brown">
                <div class="p-6 text-gray-900">
                    <div class="text-sm font-medium text-gray-500 uppercase">Total Pesanan</div>
                    <div class="text-3xl font-bold text-toreno-brown">{{ $totalOrders }}</div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-toreno-accent">
                <div class="p-6 text-gray-900">
                    <div class="text-sm font-medium text-gray-500 uppercase">Total Pendapatan</div>
                    <div class="text-3xl font-bold text-toreno-accent">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </div>
            </div>

            <!-- Total Menus -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-toreno-cream">
                <div class="p-6 text-gray-900">
                    <div class="text-sm font-medium text-gray-500 uppercase">Total Menu</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $totalMenus }}</div>
                </div>
            </div>
        </div>

        <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-semibold mb-4 text-toreno-brown">Menu Akses Cepat</h3>
                <div class="flex space-x-4">
                    <a wire:navigate href="{{ route('admin.menus') }}" class="px-4 py-2 bg-toreno-brown text-white rounded-md hover:bg-toreno-accent transition shadow-sm">Kelola Menu</a>
                    <a wire:navigate href="{{ route('admin.tables') }}" class="px-4 py-2 bg-toreno-cream text-toreno-brown rounded-md hover:bg-yellow-100 transition border border-toreno-accent shadow-sm">Kelola Meja & QR</a>
                </div>
            </div>
        </div>
    </div>
</div>
