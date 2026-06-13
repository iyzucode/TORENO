<div wire:poll.10s>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-toreno-brown">Status Pesanan</h1>
        <p class="text-gray-600 text-sm">ID: #{{ $order->order_code ?? substr($order->id, 0, 8) }}</p>
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
                @if($order->payment_method === 'qris' && $order->payment_status === 'unpaid')
                    <p class="text-sm text-gray-600 mt-2 px-2 mb-4 font-medium">Silakan scan kode QRIS di bawah ini untuk menyelesaikan pembayaran:</p>
                    
                    @if($order->snap_token)
                        <div class="bg-white p-4 rounded-3xl shadow-md border-2 border-dashed border-gray-200 inline-block mx-auto mb-4 relative group">
                            <div class="absolute inset-0 bg-white/40 backdrop-blur-sm flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-3xl z-10 cursor-pointer">
                                <svg class="w-8 h-8 text-toreno-brown mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                <span class="text-sm font-bold text-toreno-brown">Simpan QRIS</span>
                            </div>
                            <img src="{{ $order->snap_token }}" alt="QRIS Payment" class="w-64 h-64 object-contain mx-auto">
                        </div>

                        @if(app()->environment('local') || app()->environment('development'))
                            <div class="mb-4 bg-gray-50 border border-gray-200 p-3 rounded-xl max-w-sm mx-auto flex items-center">
                                <input type="text" readonly value="{{ $order->snap_token }}" class="text-[10px] text-gray-500 bg-transparent border-none focus:ring-0 w-full" id="qrisUrl">
                                <button onclick="navigator.clipboard.writeText(document.getElementById('qrisUrl').value); alert('URL disalin!');" class="ml-2 text-xs bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1.5 rounded-lg transition-colors whitespace-nowrap">
                                    Copy URL
                                </button>
                            </div>
                        @endif

                        <div class="bg-blue-50 text-blue-800 text-xs p-3 rounded-xl mb-4 border border-blue-100 flex items-start text-left">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p>Sistem akan otomatis memverifikasi pembayaran Anda. Status akan diperbarui segera setelah sukses.</p>
                        </div>
                    @else
                        <p class="text-xs text-red-500 mt-2">QR Code sedang diproses atau tidak ditemukan. Silakan hubungi kasir.</p>
                    @endif
                @else
                    <p class="text-xs text-gray-500 mt-5 px-2">Silakan menuju Kasir dan sebutkan <strong class="text-toreno-brown">Meja {{ $order->table->table_number ?? '-' }}</strong> atau <strong class="text-toreno-brown">Nama {{ $order->customer_name }}</strong> untuk melakukan pembayaran.</p>
                @endif
            @elseif($order->status === 'cooking')
                <p class="text-xs text-gray-500 mt-5 px-2">Pesanan Anda sedang disiapkan oleh dapur kami. Harap tunggu sebentar di meja Anda.</p>
            @elseif($order->status === 'completed')
                <p class="text-xs text-gray-500 mt-5 px-2">Pesanan Anda sudah siap. Selamat menikmati!</p>
            @elseif($order->status === 'cancelled')
                <p class="text-xs text-red-500 mt-5 px-2">Pesanan ini telah dibatalkan.</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
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
        
        <div class="space-y-2 mt-4 pt-4 border-t border-gray-100 text-sm">
            <div class="flex justify-between items-center text-gray-500">
                <span>Subtotal</span>
                <span>Rp {{ number_format($order->subtotal ?? $order->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center text-gray-500">
                <span>Pajak ({{ $order->tax_rate }}%)</span>
                <span>Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center text-gray-500">
                <span>{{ $order->service_charge_type === 'fixed' ? 'Biaya Layanan' : 'Biaya Layanan (' . floatval($order->service_charge_rate) . '%)' }}</span>
                <span>Rp {{ number_format($order->service_charge_amount, 0, ',', '.') }}</span>
            </div>
        </div>
        
        <div class="flex justify-between text-base font-bold mt-4 pt-4 border-t border-gray-100">
            <span class="text-gray-800">Total Pembayaran</span>
            <span class="text-toreno-brown">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Script to clear cart from local storage since the order is placed -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            localStorage.removeItem('toreno_cart');
            if (window.Alpine) {
                // If Alpine is already loaded, try to clear the store too
                try {
                    Alpine.store('cart').clear();
                } catch (e) {}
            }
        });
    </script>
</div>
