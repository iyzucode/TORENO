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

        @php
            $statusGifs = [
                'pending' => 'https://res.cloudinary.com/dtbut0lkj/image/upload/v1781415319/statusorderpay_msotjq.gif',
                'cooking' => 'https://res.cloudinary.com/dtbut0lkj/image/upload/v1781413982/statusorderbarista_sriwly.gif',
                'completed' => 'https://res.cloudinary.com/dtbut0lkj/image/upload/v1781413983/statusorderdrink_dx6gox.gif',
            ];
        @endphp

        <div class="text-center py-2">
            @if(isset($statusGifs[$order->status]))
                <div class="flex justify-center mb-6">
                    <div class="w-full max-w-md aspect-video rounded-2xl overflow-hidden border border-gray-100 bg-gray-50 flex items-center justify-center">
                        <img src="{{ $statusGifs[$order->status] }}" alt="Status Animation" class="w-full h-full object-cover mix-blend-multiply">
                    </div>
                </div>
            @endif
            
            <div class="inline-block px-6 py-2 rounded-full border-2 font-bold text-lg {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }} shadow-sm mb-3">
                {{ $statuses[$order->status] ?? $order->status }}
            </div>
            
            <p class="text-gray-500 text-xs max-w-xs mx-auto">
                @if($order->status === 'pending')
                    Selesaikan pembayaran Anda agar pesanan segera diproses.
                @elseif($order->status === 'cooking')
                    Pesanan Anda sedang disiapkan oleh tim dapur kami. Harap bersabar!
                @elseif($order->status === 'completed')
                    Pesanan Anda sudah siap! Silakan nikmati hidangan Anda.
                @else
                    Pesanan ini telah dibatalkan.
                @endif
            </p>
        </div>
    </div>

    @if($order->status === 'pending')
    <!-- Payment / QRIS Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6 text-center">
        <h3 class="font-bold text-gray-800 mb-2">Instruksi Pembayaran</h3>
        @if($order->payment_method === 'qris' && $order->payment_status === 'unpaid')
            <p class="text-sm text-gray-600 mb-4 font-medium">Silakan scan kode QRIS di bawah ini untuk menyelesaikan pembayaran:</p>
            
            @if($order->snap_token)
                <div class="bg-white p-4 rounded-3xl shadow-md border-2 border-dashed border-gray-200 inline-block mx-auto mb-4 relative group w-full max-w-xs">
                    <div class="mb-3 border-b border-dashed border-gray-200 pb-3">
                        <div class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-1">TORENO</div>
                        <div class="text-2xl font-black text-toreno-brown">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-0 bg-white/60 backdrop-blur-sm flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-xl z-10 cursor-pointer" onclick="window.location.href='{{ route('download.image', ['url' => $order->snap_token, 'name' => 'QRIS-' . ($order->order_code ?? substr($order->id, 0, 8))]) }}'">
                            <svg class="w-8 h-8 text-toreno-brown mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            <span class="text-sm font-bold text-toreno-brown">Simpan QRIS</span>
                        </div>
                        <img src="{{ $order->snap_token }}" alt="QRIS Payment" class="w-64 h-64 object-contain mx-auto">
                    </div>
                </div>

                <a href="{{ route('download.image', ['url' => $order->snap_token, 'name' => 'QRIS-' . ($order->order_code ?? substr($order->id, 0, 8))]) }}" class="mb-4 bg-toreno-brown hover:bg-toreno-accent text-white font-bold py-2.5 px-4 rounded-xl shadow-md transition active:scale-95 flex justify-center items-center max-w-xs mx-auto">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Download QRIS
                </a>

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
            <p class="text-sm text-gray-600 mt-2">Silakan menuju Kasir dan sebutkan <strong class="text-toreno-brown">Meja {{ $order->table->table_number ?? '-' }}</strong> atau <strong class="text-toreno-brown">Nama {{ $order->customer_name }}</strong> untuk melakukan pembayaran.</p>
        @endif
    </div>
    @endif

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
        
        <div class="space-y-3 mt-6 pt-5 border-t border-gray-100 text-sm">
            <div class="flex justify-between items-center text-gray-500">
                <span>Subtotal</span>
                <span>Rp {{ number_format($order->subtotal ?? $order->total_amount, 0, ',', '.') }}</span>
            </div>
            @if(isset($order->discount_amount) && $order->discount_amount > 0)
            <div class="flex justify-between items-center text-green-600 font-medium">
                <span>Diskon ({{ $order->promoCode->code ?? 'Promo' }})</span>
                <span>- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="flex justify-between items-center text-gray-500">
                <span>Pajak ({{ floatval($order->tax_rate) }}%)</span>
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
        
        @if($order->status === 'completed')
        <div class="mt-6 pt-5 border-t border-dashed border-gray-200">
            <button onclick="downloadReceipt()" class="w-full bg-toreno-brown hover:bg-toreno-accent text-white font-bold py-3 px-6 rounded-xl shadow-sm transition active:scale-95 text-sm flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Download Receipt
            </button>
        </div>
        @endif
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

    <!-- Hidden Receipt Container for Download -->
    <div id="receipt-container" class="bg-white w-[400px] p-6 shadow-none text-gray-800" style="position: absolute; left: -9999px; top: -9999px; z-index: -9999; font-family: 'Courier New', Courier, monospace;">
        <div class="text-center border-b-2 border-dashed border-gray-300 pb-4 mb-4">
            <h1 class="text-3xl font-black text-toreno-brown mb-1">TORENO</h1>
            <p class="text-sm text-gray-500">Coffee Shop & Eatery</p>
        </div>
        
        <div class="mb-4 text-sm font-semibold text-gray-700">
            <div class="flex justify-between mb-1"><span>No Order:</span> <span>#{{ $order->order_code ?? substr($order->id, 0, 8) }}</span></div>
            <div class="flex justify-between mb-1"><span>Tanggal:</span> <span>{{ $order->created_at->format('d/m/Y H:i') }}</span></div>
            <div class="flex justify-between mb-1"><span>Pelanggan:</span> <span>{{ $order->customer_name }}</span></div>
            <div class="flex justify-between mb-1"><span>Meja:</span> <span>{{ $order->table->table_number ?? '-' }}</span></div>
        </div>
        
        <div class="border-t-2 border-b-2 border-dashed border-gray-300 py-3 mb-3 text-xs">
            @foreach($order->items as $item)
                <div class="mb-2 font-semibold text-gray-800">
                    <div class="text-left leading-tight">{{ $item->quantity }}x {{ $item->menu->name ?? 'Menu' }}</div>
                    <div class="text-right mt-0.5">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                </div>
            @endforeach
        </div>
        
        <div class="text-sm border-b-2 border-gray-800 pb-3 mb-4 relative">
            <!-- Cap LUNAS -->
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-30 z-0">
                <div class="transform -rotate-12 border-4 border-red-500 text-red-500 font-black text-4xl px-4 py-1 rounded-md uppercase tracking-widest" style="font-family: Arial, sans-serif;">
                    LUNAS
                </div>
            </div>

            <div class="flex justify-between text-gray-600 mb-1 font-semibold relative z-10">
                <span>Subtotal</span>
                <span>Rp {{ number_format($order->subtotal ?? $order->total_amount, 0, ',', '.') }}</span>
            </div>
            @if(isset($order->discount_amount) && $order->discount_amount > 0)
            <div class="flex justify-between text-gray-600 mb-1 font-semibold relative z-10">
                <span>Diskon ({{ $order->promoCode->code ?? 'Promo' }})</span>
                <span>- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="flex justify-between text-gray-600 mb-1 font-semibold relative z-10">
                <span>Pajak ({{ floatval($order->tax_rate) }}%)</span>
                <span>Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-gray-600 mb-1 font-semibold relative z-10">
                <span>{{ $order->service_charge_type === 'fixed' ? 'Biaya Layanan' : 'Biaya Layanan (' . floatval($order->service_charge_rate) . '%)' }}</span>
                <span>Rp {{ number_format($order->service_charge_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between font-black text-lg mt-3 text-toreno-brown relative z-10">
                <span>TOTAL</span>
                <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
        
        <div class="text-center text-xs text-gray-500 font-semibold pt-2">
            <p>Terima kasih atas pesanan Anda!</p>
            <p class="italic mt-1">Layanan kasir & digital by TORENO</p>
        </div>
    </div>

    <!-- html2canvas Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        function downloadReceipt() {
            const btn = event.currentTarget;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
            btn.disabled = true;

            const container = document.getElementById('receipt-container');
            
            html2canvas(container, {
                scale: 2, // High resolution
                backgroundColor: '#ffffff',
                logging: false
            }).then(canvas => {
                const imgData = canvas.toDataURL('image/jpeg', 1.0);
                const link = document.createElement('a');
                link.download = `Receipt-TORENO-{{ $order->order_code ?? substr($order->id, 0, 8) }}.jpg`;
                link.href = imgData;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                // Restore button
                btn.innerHTML = originalText;
                btn.disabled = false;
            }).catch(err => {
                console.error("Error generating receipt", err);
                alert("Terjadi kesalahan saat membuat receipt.");
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }
    </script>
</div>
