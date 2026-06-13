<div x-data>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-toreno-brown">Keranjang</h1>
            <p class="text-gray-600 text-sm">Cek kembali pesanan Anda</p>
        </div>
        <a wire:navigate href="{{ route('customer.catalog', ['qr_hash' => session('qr_hash', 'demo')]) }}" class="text-sm font-bold text-toreno-accent hover:underline flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali
        </a>
    </div>

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
            <div class="space-y-4 mb-6">
                <template x-for="item in Object.values($store.cart.items)" :key="item.id">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex flex-col gap-3"
                         x-data="{ showNoteModal: false, tempNote: '' }">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-gray-800" x-text="item.name"></h3>
                                <p class="text-toreno-brown font-extrabold text-sm" x-text="'Rp ' + (item.price * item.quantity).toLocaleString('id-ID')"></p>
                            </div>
                            <button @click="$store.cart.remove(item.id)" class="text-red-400 hover:text-red-600 p-1 transition" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <!-- Tombol Catatan -->
                            <button @click="tempNote = item.notes || ''; showNoteModal = true"
                                    class="flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-lg transition active:scale-95"
                                    :class="item.notes 
                                        ? 'bg-toreno-accent/10 text-toreno-accent border border-toreno-accent/30 font-semibold' 
                                        : 'bg-gray-100 text-gray-500 hover:bg-gray-200 border border-gray-200'">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                <span x-text="item.notes ? 'Catatan' : 'Catatan'"></span>
                            </button>

                            <!-- Preview catatan -->
                            <template x-if="item.notes">
                                <span class="text-[10px] text-gray-400 italic truncate max-w-[100px] mx-2" x-text="item.notes"></span>
                            </template>

                            <!-- Quantity -->
                            <div class="flex items-center bg-gray-50 border border-gray-200 rounded-lg overflow-hidden shadow-sm ml-auto">
                                <button @click="$store.cart.decrease(item.id)" class="w-8 h-8 flex items-center justify-center text-toreno-brown hover:bg-gray-200 font-bold transition active:scale-95">-</button>
                                <span class="w-8 text-center text-sm font-black text-gray-800" x-text="item.quantity"></span>
                                <button @click="$store.cart.increase(item.id)" class="w-8 h-8 flex items-center justify-center text-toreno-brown hover:bg-gray-200 font-bold transition active:scale-95">+</button>
                            </div>
                        </div>

                        <!-- Modal Pop Up Catatan -->
                        <template x-teleport="body">
                            <div x-show="showNoteModal" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="fixed inset-0 z-[100] flex items-center justify-center p-6"
                                 @keydown.escape.window="showNoteModal = false">
                                
                                <!-- Overlay -->
                                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showNoteModal = false"></div>
                                
                                <!-- Modal Content -->
                                <div x-show="showNoteModal"
                                     x-transition:enter="transition ease-out duration-200 delay-75"
                                     x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-90"
                                     class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden border border-gray-100 p-5">
                                    
                                    <!-- Header Minimalis -->
                                    <div class="flex items-center justify-between mb-3">
                                        <h3 class="font-bold text-gray-800 text-sm">Catatan: <span x-text="item.name"></span></h3>
                                        <button @click="showNoteModal = false" class="text-gray-400 hover:text-gray-600 transition p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                    
                                    <!-- Body -->
                                    <div>
                                        <textarea x-model="tempNote" 
                                                  x-ref="noteInput"
                                                  @keydown.ctrl.enter="$store.cart.updateNotes(item.id, tempNote); showNoteModal = false"
                                                  @keydown.meta.enter="$store.cart.updateNotes(item.id, tempNote); showNoteModal = false"
                                                  placeholder="Contoh: manis sedang, tanpa es, extra shot..."
                                                  rows="3"
                                                  class="w-full text-sm border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-toreno-accent/30 focus:border-toreno-accent transition-all resize-none placeholder:text-gray-400"
                                                  x-init="$watch('showNoteModal', val => { if(val) $nextTick(() => $refs.noteInput.focus()) })"></textarea>
                                        <p class="text-[10px] text-gray-400 mt-1.5">Tuliskan permintaan khusus untuk menu ini.</p>
                                    </div>
                                    
                                    <!-- Footer -->
                                    <div class="flex justify-end mt-4">
                                        <button @click="$store.cart.updateNotes(item.id, tempNote); showNoteModal = false" 
                                                class="py-2 px-5 rounded-xl bg-toreno-brown hover:bg-toreno-accent text-white font-bold text-xs shadow-sm transition active:scale-95 flex items-center justify-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Simpan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <!-- Total Bar -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-24">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-500 font-medium">Total Pembayaran</span>
                    <span class="text-xl font-black text-toreno-brown" x-text="'Rp ' + $store.cart.totalAmount.toLocaleString('id-ID')"></span>
                </div>
                <a wire:navigate href="{{ route('customer.checkout') }}" class="block w-full bg-toreno-brown hover:bg-toreno-accent text-white text-center font-bold py-3.5 px-4 rounded-xl shadow-md transition text-lg active:scale-95">
                    Lanjut Checkout
                </a>
            </div>
        </div>
    </template>
</div>
