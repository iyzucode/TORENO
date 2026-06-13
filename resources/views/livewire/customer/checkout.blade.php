<div x-data>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-toreno-brown">Checkout</h1>
        <p class="text-gray-600 text-sm">Lengkapi data pesanan Anda</p>
    </div>

    @error('cart')
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ $message }}</span>
        </div>
    @enderror

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
        <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Informasi Meja</h3>
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Nomor Meja</span>
            <span class="font-bold text-toreno-brown text-lg">{{ session('table_number', '-') }}</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
        <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Data Pemesan</h3>
        <div class="mb-2">
            <label for="customer_name" class="block text-gray-700 text-xs font-bold mb-2">Nama Anda:</label>
            <input type="text" id="customer_name" wire:model="customer_name" placeholder="Masukkan nama Anda" class="shadow-sm border-gray-300 focus:border-toreno-accent focus:ring focus:ring-toreno-accent focus:ring-opacity-50 rounded-xl w-full text-sm py-3 px-4">
            @error('customer_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-24">
        <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Rincian Pesanan</h3>
        
        <template x-for="item in Object.values($store.cart.items)" :key="item.id">
            <div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-600"><span class="font-bold text-gray-800" x-text="item.quantity + 'x'"></span> <span x-text="item.name"></span></span>
                    <span class="font-medium text-gray-800" x-text="'Rp ' + (item.price * item.quantity).toLocaleString('id-ID')"></span>
                </div>
                <template x-if="item.notes">
                    <div class="text-[10px] text-gray-400 ml-5 mb-2 italic" x-text="'Catatan: ' + item.notes"></div>
                </template>
            </div>
        </template>
        
        <div class="flex justify-between text-base font-bold mt-4 pt-4 border-t border-gray-100">
            <span class="text-gray-800">Total Pembayaran</span>
            <span class="text-toreno-brown" x-text="'Rp ' + $store.cart.totalAmount.toLocaleString('id-ID')"></span>
        </div>
        
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-xl p-3 text-xs text-yellow-800 flex items-start shadow-sm">
            <svg class="w-5 h-5 mr-2 flex-shrink-0 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p>Silakan lakukan pembayaran secara langsung <strong>(CASH)</strong> ke Kasir setelah pesanan dikonfirmasi.</p>
        </div>

        <button @click="$wire.processCheckout(Object.values($store.cart.items))" wire:loading.attr="disabled" class="w-full mt-6 bg-toreno-brown hover:bg-toreno-accent text-white font-bold py-3 px-4 rounded-xl shadow-md transition active:scale-95 flex items-center justify-center">
            <span wire:loading.remove>Konfirmasi Pesanan</span>
            <span wire:loading>Memproses...</span>
        </button>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('clearCart', () => {
                Alpine.store('cart').clear();
            });
        });
    </script>
</div>
