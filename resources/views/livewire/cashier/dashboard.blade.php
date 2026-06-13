<x-slot name="header">
    <h2 class="font-semibold text-xl text-toreno-brown leading-tight">
        {{ __('Dashboard Kasir') }}
    </h2>
</x-slot>

<div class="py-12 bg-toreno-light min-h-screen" wire:poll.5s>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 shadow-sm" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <h3 class="text-xl font-bold text-gray-800 mb-4 border-b-2 border-toreno-cream pb-2">Pesanan Baru (Menunggu Pembayaran)</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            @forelse($pendingOrders as $order)
                <div class="bg-white rounded-xl shadow-md border-t-4 border-yellow-400 overflow-hidden transform transition hover:-translate-y-1">
                    <div class="p-4 border-b border-gray-100 bg-yellow-50 flex justify-between items-center">
                        <div>
                            <span class="font-bold text-lg text-toreno-brown">Meja {{ $order->table->table_number ?? '-' }}</span>
                            <p class="text-xs text-gray-600 font-medium mt-1">Pemesan: {{ $order->customer_name }}</p>
                        </div>
                        <span class="text-xs bg-yellow-200 text-yellow-800 px-2 py-1 rounded font-bold shadow-sm">Pending</span>
                    </div>
                    <div class="p-4">
                        <ul class="text-sm mb-4 space-y-2">
                            @foreach($order->items as $item)
                                <li class="flex justify-between border-b border-dashed border-gray-100 pb-1 last:border-0 last:pb-0">
                                    <span class="text-gray-600"><span class="font-bold text-gray-800">{{ $item->quantity }}x</span> {{ $item->menu->name ?? 'Menu' }}</span>
                                    <span class="font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="flex justify-between items-center border-t border-gray-200 pt-3 mb-4">
                            <span class="font-bold text-gray-700">Total Tagihan:</span>
                            <span class="font-bold text-toreno-brown text-xl">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                        <button wire:click="confirmPayment('{{ $order->id }}')" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded-xl shadow-md transition active:scale-95 flex justify-center items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Konfirmasi Lunas (Cash)
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full p-8 bg-white rounded-xl text-center text-gray-400 border border-dashed border-gray-300">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    Belum ada pesanan baru yang menunggu pembayaran.
                </div>
            @endforelse
        </div>

        <h3 class="text-xl font-bold text-gray-800 mb-4 border-b-2 border-toreno-cream pb-2">Pesanan Sedang Diproses Dapur</h3>
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-gray-600">ID Pesanan</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Meja</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Pemesan</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Total</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($cookingOrders as $order)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-gray-500">#{{ $order->order_code ?? substr($order->id, 0, 8) }}</td>
                                <td class="px-6 py-4 font-bold text-toreno-brown">{{ $order->table->table_number ?? '-' }}</td>
                                <td class="px-6 py-4 font-medium text-gray-800">{{ $order->customer_name }}</td>
                                <td class="px-6 py-4 font-medium text-toreno-accent">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $order->updated_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">Tidak ada pesanan yang sedang diproses oleh dapur.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($playAudio)
        <script>
            document.addEventListener('livewire:initialized', () => {
                const context = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = context.createOscillator();
                const gainNode = context.createGain();
                
                oscillator.type = 'sine';
                oscillator.frequency.setValueAtTime(880, context.currentTime); // A5
                oscillator.frequency.exponentialRampToValueAtTime(440, context.currentTime + 0.5); // A4
                
                gainNode.gain.setValueAtTime(1, context.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, context.currentTime + 1);
                
                oscillator.connect(gainNode);
                gainNode.connect(context.destination);
                
                oscillator.start();
                oscillator.stop(context.currentTime + 1);
            });
        </script>
    @endif
</div>
