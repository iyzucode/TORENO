<x-slot name="header">
    <h2 class="font-semibold text-xl text-toreno-brown leading-tight">
        {{ __('Dashboard Dapur') }}
    </h2>
</x-slot>

<div class="py-12 bg-toreno-light min-h-screen" wire:poll.5s>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 shadow-sm" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <h3 class="text-xl font-bold text-gray-800 mb-4 border-b-2 border-toreno-cream pb-2">Antrian Dapur (Sedang Diproses)</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            @forelse($cookingOrders as $order)
                <div class="bg-white rounded-xl shadow-md border-t-4 border-toreno-accent overflow-hidden transform transition hover:-translate-y-1">
                    <div class="p-4 border-b border-gray-100 bg-orange-50 flex justify-between items-center">
                        <div>
                            <span class="font-bold text-lg text-toreno-brown">Meja {{ $order->table->table_number ?? '-' }}</span>
                            <p class="text-xs text-gray-600 font-medium mt-1">Pemesan: {{ $order->customer_name }}</p>
                        </div>
                        <span class="text-xs bg-orange-200 text-orange-800 px-2 py-1 rounded font-bold shadow-sm flex items-center">
                            <svg class="w-3 h-3 mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            {{ $order->updated_at->diffForHumans() }}
                        </span>
                    </div>
                    <div class="p-4">
                        <ul class="text-sm mb-4 space-y-3">
                            @foreach($order->items as $item)
                                <li class="border-b border-dashed border-gray-200 pb-2 last:border-0 last:pb-0">
                                    <div class="flex justify-between font-bold text-gray-800 text-base">
                                        <span>{{ $item->quantity }}x {{ $item->menu->name ?? 'Menu' }}</span>
                                    </div>
                                    @if(!empty($item->notes))
                                        <div class="text-sm text-red-600 mt-1 flex items-start font-medium bg-red-50 p-2 rounded">
                                            <svg class="w-4 h-4 mr-1 flex-shrink-0 mt-0.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            Catatan: {{ $item->notes }}
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        <button wire:click="markAsCompleted('{{ $order->id }}')" class="w-full mt-4 bg-toreno-brown hover:bg-toreno-accent text-white font-bold py-3 px-4 rounded-xl shadow-md transition text-lg flex items-center justify-center active:scale-95">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Pesanan Selesai
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full p-12 bg-white rounded-xl text-center text-gray-400 border border-dashed border-gray-300">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path></svg>
                    <p class="text-xl font-medium text-gray-500">Hore! Tidak ada antrian pesanan saat ini.</p>
                </div>
            @endforelse
        </div>

        <h3 class="text-xl font-bold text-gray-800 mb-4 border-b-2 border-toreno-cream pb-2 mt-8">Selesai Dibuat (Hari Ini)</h3>
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-gray-600">ID Pesanan</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Meja</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Pemesan</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Waktu Selesai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($completedOrders as $order)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-gray-500">#{{ substr($order->id, 0, 8) }}</td>
                                <td class="px-6 py-4 font-bold text-toreno-brown">{{ $order->table->table_number ?? '-' }}</td>
                                <td class="px-6 py-4 font-medium text-gray-800">{{ $order->customer_name }}</td>
                                <td class="px-6 py-4 font-bold text-green-600">{{ $order->updated_at->format('H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada pesanan yang diselesaikan hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
