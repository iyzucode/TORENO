<div>
    @php
        $quotes = [
            "Mengaduk kopi, mengadu sepi. Berkisah lagi tentang patah hati, semoga pelukanmu kelak akan melengkapi.",
            "Ketika kopi menjadi sahabat sejati, pagi bukan lagi sebuah misteri. Seperti kamu yang slalu ada di hati, slalu mengisi hari-hari.",
            "Biji kopi yang sama bisa jadi citarasa kopi lain di tangan yang beda. sama seperti kasih sayang jika di tangan yang beda maka beda pula kisahnya.",
            "Di secangkir kopi, biarlah aku menjelma menjadi apa saja yang kau inginkan, menjadi pahit ataupun manis asal tetap kau rindukan."
        ];
        $randomQuote = $quotes[array_rand($quotes)];
    @endphp

    <div class="mb-8 bg-white border border-gray-100 p-5 rounded-2xl shadow-sm relative overflow-hidden flex items-start gap-3">
        <svg class="w-6 h-6 text-toreno-accent/60 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M13 14.725c0-5.141 3.892-10.519 10-11.725l.984 2.126c-2.215.835-4.163 3.742-4.38 5.746 2.491.392 4.396 2.547 4.396 5.149 0 3.182-2.584 4.979-5.199 4.979-3.015 0-5.801-2.305-5.801-6.275zm-13 0c0-5.141 3.892-10.519 10-11.725l.984 2.126c-2.215.835-4.163 3.742-4.38 5.746 2.491.392 4.396 2.547 4.396 5.149 0 3.182-2.584 4.979-5.199 4.979-3.015 0-5.801-2.305-5.801-6.275z"/></svg>
        <p class="text-sm text-gray-500 italic leading-relaxed font-medium">"{{ $randomQuote }}"</p>
    </div>

    @foreach($menusByCategory as $category => $menus)
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <div class="w-1 h-5 bg-toreno-accent rounded-full mr-2"></div>
                <h2 class="text-lg font-bold text-gray-800 tracking-tight">
                    {{ $category }}
                </h2>
            </div>
            
            <div class="flex flex-col gap-4" x-data>
                @foreach($menus as $menu)
                    <div wire:key="menu-{{ $menu->id }}" class="bg-white rounded-2xl shadow-sm hover:shadow-md border border-gray-100 p-3 flex flex-col transition duration-200">
                        <div class="flex gap-4 items-center">
                            <!-- Gambar (Fixed Size) -->
                            <div class="flex-shrink-0 bg-gray-50 rounded-xl overflow-hidden relative border border-gray-100" style="width: 96px; height: 96px; min-width: 96px; min-height: 96px;">
                                @if($menu->image_url)
                                    <img src="{{ asset('storage/' . $menu->image_url) }}" alt="{{ $menu->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div class="flex items-center justify-center text-gray-400 bg-toreno-cream/30" style="width: 100%; height: 100%;">
                                        <svg class="w-8 h-8 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Informasi -->
                            <div class="flex-grow py-1 flex flex-col h-full justify-between">
                                <div>
                                    <h3 class="font-bold text-gray-800 text-sm leading-tight">{{ $menu->name }}</h3>
                                    <p class="text-[11px] text-gray-500 mt-1 line-clamp-2 leading-relaxed">{{ $menu->description }}</p>
                                </div>
                                <div class="flex justify-between items-center mt-3">
                                    <p class="text-toreno-brown font-extrabold text-sm tracking-tight">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                                    
                                    <template x-if="!$store.cart.items['{{ $menu->id }}']">
                                        <button @click="$store.cart.add({id: '{{ $menu->id }}', name: '{{ addslashes($menu->name) }}', price: {{ $menu->price }}})" class="bg-toreno-brown hover:bg-toreno-accent text-white text-xs font-bold py-1.5 px-4 rounded-xl shadow-sm transition active:scale-95 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                            Tambah
                                        </button>
                                    </template>

                                    <template x-if="$store.cart.items['{{ $menu->id }}']">
                                        <div class="flex items-center bg-gray-50 border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                                            <button @click="$store.cart.decrease('{{ $menu->id }}')" class="w-8 h-8 flex items-center justify-center text-toreno-brown hover:bg-gray-200 font-bold transition active:scale-95">-</button>
                                            <span class="w-6 text-center text-sm font-black text-gray-800" x-text="$store.cart.items['{{ $menu->id }}'].quantity"></span>
                                            <button @click="$store.cart.increase('{{ $menu->id }}')" class="w-8 h-8 flex items-center justify-center text-toreno-brown hover:bg-gray-200 font-bold transition active:scale-95">+</button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Notes Button (Tampil Jika Ada di Keranjang) -->
                        <template x-if="$store.cart.items['{{ $menu->id }}']">
                            <div class="mt-2 pt-2 border-t border-gray-100 flex items-center gap-2"
                                 x-data="{ showNoteModal: false, tempNote: '' }">
                                
                                <!-- Tombol Catatan -->
                                <button @click="tempNote = $store.cart.items['{{ $menu->id }}']?.notes || ''; showNoteModal = true"
                                        class="flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-lg transition active:scale-95"
                                        :class="$store.cart.items['{{ $menu->id }}']?.notes 
                                            ? 'bg-toreno-accent/10 text-toreno-accent border border-toreno-accent/30 font-semibold' 
                                            : 'bg-gray-100 text-gray-500 hover:bg-gray-200 border border-gray-200'">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    <span x-text="$store.cart.items['{{ $menu->id }}']?.notes ? 'Catatan' : 'Catatan'"></span>
                                </button>

                                <!-- Preview catatan jika ada -->
                                <template x-if="$store.cart.items['{{ $menu->id }}']?.notes">
                                    <span class="text-[10px] text-gray-400 italic truncate max-w-[140px]" x-text="$store.cart.items['{{ $menu->id }}'].notes"></span>
                                </template>

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
                                                <h3 class="font-bold text-gray-800 text-sm">Catatan: {{ $menu->name }}</h3>
                                                <button @click="showNoteModal = false" class="text-gray-400 hover:text-gray-600 transition p-1">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </div>
                                            
                                            <!-- Body -->
                                            <div>
                                                <textarea x-model="tempNote" 
                                                          x-ref="noteInput"
                                                          @keydown.meta.enter="$store.cart.updateNotes('{{ $menu->id }}', tempNote); showNoteModal = false"
                                                          @keydown.ctrl.enter="$store.cart.updateNotes('{{ $menu->id }}', tempNote); showNoteModal = false"
                                                          placeholder="Contoh: manis sedang, tanpa es, extra shot..."
                                                          rows="3"
                                                          class="w-full text-sm border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-toreno-accent/30 focus:border-toreno-accent transition-all resize-none placeholder:text-gray-400"
                                                          x-init="$watch('showNoteModal', val => { if(val) $nextTick(() => $refs.noteInput.focus()) })"></textarea>
                                                <p class="text-[10px] text-gray-400 mt-1.5">Tuliskan permintaan khusus untuk menu ini.</p>
                                            </div>
                                            
                                            <!-- Footer -->
                                            <div class="flex justify-end mt-4">
                                                <button @click="$store.cart.updateNotes('{{ $menu->id }}', tempNote); showNoteModal = false" 
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
                @endforeach
            </div>
        </div>
    @endforeach
</div>
