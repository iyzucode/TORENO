<div x-data="{ nameError: false, phoneError: false }">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-toreno-brown">Checkout</h1>
            <p class="text-gray-600 text-sm">Lengkapi data pesanan Anda</p>
        </div>
        <a wire:navigate href="{{ route('customer.cart') }}" class="text-sm font-bold text-toreno-accent hover:underline flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali
        </a>
    </div>

    @error('cart')
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ $message }}</span>
        </div>
    @enderror

    <template x-if="$store.cart.totalCount === 0">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center mb-8">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <p class="text-gray-500 font-medium mb-4">Keranjang Anda masih kosong.</p>
            <a wire:navigate href="{{ route('customer.catalog', ['qr_hash' => session('qr_hash', 'demo')]) }}" class="inline-block bg-toreno-brown hover:bg-toreno-accent text-white font-bold py-2.5 px-6 rounded-xl shadow transition active:scale-95">
                Lihat Menu
            </a>
        </div>
    </template>

    <template x-if="$store.cart.totalCount > 0">
        <div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
        <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Informasi Meja</h3>
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Nomor Meja</span>
            <span class="font-bold text-toreno-brown text-lg">{{ session('table_number', '-') }}</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
        <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Data Pemesan</h3>
        <div class="mb-4">
            <label for="customer_name" class="block text-gray-700 text-xs font-bold mb-2">Nama Anda: <span class="text-red-500">*</span></label>
            <input type="text" id="customer_name" wire:model="customer_name" @input="nameError = false" :class="{ 'border-red-500 bg-red-50': nameError }" placeholder="Masukkan nama Anda" class="shadow-sm border-gray-300 focus:border-toreno-accent focus:ring focus:ring-toreno-accent focus:ring-opacity-50 rounded-xl w-full text-sm py-3 px-4 transition-colors">
            <span x-show="nameError" style="display: none;" class="text-red-500 text-xs mt-1 block">Nama Anda wajib diisi.</span>
            @error('customer_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="customer_phone" class="block text-gray-700 text-xs font-bold mb-2">Nomor Handphone: <span class="text-red-500">*</span></label>
            <input type="tel" id="customer_phone" wire:model="customer_phone" @input="phoneError = false" :class="{ 'border-red-500 bg-red-50': phoneError }" placeholder="Contoh: 08123456789" class="shadow-sm border-gray-300 focus:border-toreno-accent focus:ring focus:ring-toreno-accent focus:ring-opacity-50 rounded-xl w-full text-sm py-3 px-4 transition-colors">
            <span x-show="phoneError" style="display: none;" class="text-red-500 text-xs mt-1 block">Nomor handphone wajib diisi.</span>
            @error('customer_phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div class="mb-2">
            <label class="block text-gray-700 text-xs font-bold mb-3">Metode Pembayaran: <span class="text-red-500">*</span></label>
            <div class="space-y-2">
                <!-- Disembunyikan sementara sesuai permintaan -->
                <label style="display: none;" class="flex items-center p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition" :class="{ 'border-toreno-accent bg-orange-50': $wire.payment_method === 'cash' }">
                    <input type="radio" wire:model.live="payment_method" value="cash" class="text-toreno-accent focus:ring-toreno-accent mr-3">
                    <div class="flex-1">
                        <span class="block font-bold text-gray-800 text-sm">Tunai (Bayar di Kasir)</span>
                        <span class="block text-xs text-gray-500 mt-0.5">Lakukan pembayaran ke kasir dengan menyebutkan nomor meja.</span>
                    </div>
                </label>
                <label class="flex items-start gap-3 p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition" :class="{ 'border-toreno-accent bg-orange-50': $wire.payment_method === 'qris' }">
                    <input type="radio" wire:model.live="payment_method" value="qris" class="text-toreno-accent focus:ring-toreno-accent w-5 h-5 mt-0.5 flex-shrink-0">
                    <div class="flex-1">
                        <span class="block font-bold text-gray-800 text-base">QRIS</span>
                        <span class="block text-xs text-gray-500 mt-0.5">Pembayaran cashless cepat. Pesanan otomatis masuk ke dapur.</span>
                    </div>
                </label>
            </div>
            @error('payment_method') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
        <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Kupon Diskon</h3>
        <div class="flex items-center gap-2">
            <input type="text" wire:model="promoCodeInput" x-bind:disabled="$store.cart.appliedPromoCode" placeholder="Masukkan kode promo..." class="shadow-sm border-gray-300 focus:border-toreno-accent focus:ring focus:ring-toreno-accent focus:ring-opacity-50 rounded-xl w-full text-sm py-3 px-4 uppercase">
            <button type="button" x-show="!$store.cart.appliedPromoCode" wire:click="applyPromoCode($store.cart.subtotalAmount)" wire:loading.attr="disabled" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 px-5 rounded-xl shadow-sm transition active:scale-95 text-sm whitespace-nowrap">
                Gunakan
            </button>
            <button type="button" x-show="$store.cart.appliedPromoCode" @click="$store.cart.clearPromo(); $wire.removePromoCode()" class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-5 rounded-xl shadow-sm transition active:scale-95 text-sm whitespace-nowrap">
                Hapus
            </button>
        </div>
        @error('promo_code') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
        @if(session()->has('promo_success'))
            <span class="text-green-600 text-xs mt-2 block font-medium">{{ session('promo_success') }}</span>
        @endif
        
        <!-- Livewire to Alpine Bridge for Promo -->
        <div x-effect="if($wire.discountAmount > 0 && $wire.appliedPromoCode) { $store.cart.setPromo($wire.appliedPromoCode, $wire.discountAmount); } else { $store.cart.clearPromo(); }"></div>
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
        
        <div class="space-y-2 mt-4 pt-4 border-t border-gray-100 text-sm">
            <div class="flex justify-between items-center text-gray-500">
                <span>Subtotal</span>
                <span x-text="'Rp ' + $store.cart.subtotalAmount.toLocaleString('id-ID')"></span>
            </div>
            <template x-if="$store.cart.discountAmount > 0">
                <div class="flex justify-between items-center text-green-600 font-medium">
                    <span x-text="'Diskon (' + $store.cart.appliedPromoCode + ')'"></span>
                    <span x-text="'- Rp ' + $store.cart.discountAmount.toLocaleString('id-ID')"></span>
                </div>
            </template>
            <div class="flex justify-between items-center text-gray-500">
                <span x-text="'Pajak (' + $store.cart.taxRate + '%)'"></span>
                <span x-text="'Rp ' + $store.cart.taxAmount.toLocaleString('id-ID')"></span>
            </div>
            <div class="flex justify-between items-center text-gray-500">
                <span x-text="$store.cart.serviceChargeType === 'fixed' ? 'Biaya Layanan' : 'Biaya Layanan (' + $store.cart.serviceChargeRate + '%)'"></span>
                <span x-text="'Rp ' + $store.cart.serviceChargeAmount.toLocaleString('id-ID')"></span>
            </div>
        </div>
        <div class="flex justify-between text-base font-bold mt-4 pt-4 border-t border-gray-100">
            <span class="text-gray-800">Total Pembayaran</span>
            <span class="text-toreno-brown" x-text="'Rp ' + $store.cart.totalAmount.toLocaleString('id-ID')"></span>
        </div>
        
        <template x-if="$wire.payment_method === 'cash'">
            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-xl p-3 text-xs text-yellow-800 flex items-start shadow-sm transition-all duration-300">
                <svg class="w-5 h-5 mr-2 flex-shrink-0 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p>Silakan lakukan pembayaran secara langsung <strong>(CASH)</strong> ke Kasir setelah pesanan dikonfirmasi.</p>
            </div>
        </template>
        <template x-if="$wire.payment_method === 'qris'">
            <div class="mt-6 bg-green-50 border border-green-200 rounded-xl p-3 text-xs text-green-800 flex items-start shadow-sm transition-all duration-300">
                <svg class="w-5 h-5 mr-2 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p>Setelah konfirmasi, Anda akan diarahkan untuk memindai <strong>QRIS</strong>. Pembayaran otomatis diverifikasi.</p>
            </div>
        </template>

        <button type="button" @click="
            let nameInput = document.getElementById('customer_name');
            let phoneInput = document.getElementById('customer_phone');
            
            nameError = !nameInput.value || nameInput.value.trim() === '';
            phoneError = !phoneInput.value || phoneInput.value.trim() === '';

            if (nameError) {
                nameInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                setTimeout(() => nameInput.focus(), 300);
            } else if (phoneError) {
                phoneInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                setTimeout(() => phoneInput.focus(), 300);
            } else {
                $wire.processCheckout(Object.values($store.cart.items), $store.cart.subtotalAmount, $store.cart.taxAmount, $store.cart.serviceChargeAmount, $store.cart.totalAmount, $store.cart.discountAmount);
            }
        " wire:loading.attr="disabled" class="w-full mt-6 bg-toreno-brown hover:bg-toreno-accent text-white font-bold py-3 px-4 rounded-xl shadow-md transition active:scale-95 flex items-center justify-center">
            <span wire:loading.remove>Konfirmasi Pesanan</span>
            <span wire:loading>Memproses...</span>
        </button>
    </div>
        </div>
    </template>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('clearCart', () => {
                Alpine.store('cart').clear();
            });
        });
    </script>
</div>
