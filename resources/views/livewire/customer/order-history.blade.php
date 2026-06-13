<div class="mb-24">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-toreno-brown">Riwayat Pesanan</h1>
        <p class="text-gray-600 text-sm">Cari pesanan Anda dalam 24 jam terakhir</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
        <form wire:submit.prevent="search" class="space-y-4">
            <div>
                <label for="phone_number" class="block text-gray-700 text-xs font-bold mb-2">Nomor Handphone</label>
                <input type="tel" id="phone_number" wire:model="phone_number" placeholder="Contoh: 08123456789" class="shadow-sm border-gray-300 focus:border-toreno-accent focus:ring focus:ring-toreno-accent focus:ring-opacity-50 rounded-xl w-full text-sm py-3 px-4">
                @error('phone_number') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            <button type="submit" wire:loading.attr="disabled" class="w-full bg-toreno-brown hover:bg-toreno-accent text-white font-bold py-3 px-4 rounded-xl shadow-md transition active:scale-95 flex items-center justify-center">
                <span wire:loading.remove>Cari Pesanan</span>
                <span wire:loading>Mencari...</span>
            </button>
        </form>
    </div>

    @if($hasSearched)
        <div class="mt-8">
            <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-200 pb-2">Hasil Pencarian</h3>
            
            @if(count($orders) > 0)
                <div class="space-y-4">
                    @php
                        $statuses = [
                            'pending' => 'Menunggu Pembayaran',
                            'cooking' => 'Diproses Dapur',
                            'completed' => 'Selesai / Siap',
                            'cancelled' => 'Dibatalkan'
                        ];
                        
                        $statusColors = [
                            'pending' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
                            'cooking' => 'bg-blue-50 border-blue-200 text-blue-800',
                            'completed' => 'bg-green-50 border-green-200 text-green-800',
                            'cancelled' => 'bg-red-50 border-red-200 text-red-800',
                        ];
                    @endphp

                    @foreach($orders as $order)
                        <div wire:click="viewOrder('{{ $order->id }}')" class="rounded-2xl border-2 {{ $statusColors[$order->status] ?? 'bg-white border-gray-100 text-gray-800' }} p-4 cursor-pointer shadow-sm hover:shadow-md transition-all active:scale-[0.98]">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <div class="font-bold text-sm">#{{ $order->order_code ?? substr($order->id, 0, 8) }}</div>
                                    <div class="text-xs opacity-75 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</div>
                                </div>
                                <div class="text-xs font-bold px-2.5 py-1 rounded-full bg-white bg-opacity-50 border border-current shadow-sm">
                                    {{ $statuses[$order->status] ?? $order->status }}
                                </div>
                            </div>
                            <div class="flex justify-between items-end mt-4 pt-3 border-t border-current border-opacity-20">
                                <div class="text-xs opacity-80">
                                    Meja: <span class="font-bold">{{ $order->table->table_number ?? '-' }}</span><br>
                                    Atas Nama: <span class="font-bold">{{ $order->customer_name }}</span>
                                </div>
                                <div class="font-black text-sm">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-gray-500 text-sm">Tidak ada pesanan ditemukan<br>dalam 24 jam terakhir untuk nomor tersebut.</p>
                </div>
            @endif
        </div>
    @endif
</div>
