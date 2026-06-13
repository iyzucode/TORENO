<div wire:poll.10s>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-toreno-brown">Status Pesanan</h1>
        <p class="text-gray-600 text-sm">ID: #{{ substr($order->id, 0, 8) }}</p>
    </div>

    <!-- Status Tracking -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
        @php
            $statuses = [
                'pending' => 'Menunggu Pembayaran',
                'cooking' => 'Diproses Dapur',
                'completed' => 'Selesai / Siap',
                'cancelled' => 'Dibatalkan'
            ];
            
            $statusColors = [
                'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                'cooking' => 'bg-blue-100 text-blue-800 border-blue-200',
                'completed' => 'bg-green-100 text-green-800 border-green-200',
                'cancelled' => 'bg-red-100 text-red-800 border-red-200',
            ];
        @endphp

        <div class="text-center py-4">
            <p class="text-sm text-gray-500 mb-3">Status Saat Ini:</p>
            <div class="inline-block px-5 py-2.5 rounded-full border-2 font-bold text-sm {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }} shadow-sm transition-all duration-500">
                {{ $statuses[$order->status] ?? $order->status }}
            </div>
            
            @if($order->status === 'pending')
                <p class="text-xs text-gray-500 mt-5 px-2">Silakan menuju Kasir dan sebutkan <strong class="text-toreno-brown">Meja {{ $order->table->table_number ?? '-' }}</strong> atau <strong class="text-toreno-brown">Nama {{ $order->customer_name }}</strong> untuk melakukan pembayaran.</p>
            @elseif($order->status === 'cooking')
                <p class="text-xs text-gray-500 mt-5 px-2">Pesanan Anda sedang disiapkan oleh dapur kami. Harap tunggu sebentar di meja Anda.</p>
            @elseif($order->status === 'completed')
                <p class="text-xs text-gray-500 mt-5 px-2">Pesanan Anda sudah siap. Selamat menikmati!</p>
            @elseif($order->status === 'cancelled')
                <p class="text-xs text-red-500 mt-5 px-2">Pesanan ini telah dibatalkan.</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-24">
        <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Rincian Pesanan</h3>
        
        <div class="flex justify-between text-sm mb-4">
            <span class="text-gray-500">Nama Pemesan:</span>
            <span class="font-bold text-gray-800">{{ $order->customer_name }}</span>
        </div>
        
        @foreach($order->items as $item)
            <div class="flex justify-between text-sm mb-2">
                <span class="text-gray-600"><span class="font-bold text-gray-800">{{ $item->quantity }}x</span> {{ $item->menu->name ?? 'Menu' }}</span>
                <span class="font-medium text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
            </div>
            @if(!empty($item->notes))
                <div class="text-[10px] text-gray-400 ml-5 mb-2 italic">Catatan: {{ $item->notes }}</div>
            @endif
        @endforeach
        
        <div class="flex justify-between text-base font-bold mt-4 pt-4 border-t border-gray-100">
            <span class="text-gray-800">Total Pembayaran</span>
            <span class="text-toreno-brown">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
        </div>
    </div>
</div>
