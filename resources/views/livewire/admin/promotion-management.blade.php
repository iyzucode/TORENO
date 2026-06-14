<x-slot name="header">
    <h2 class="font-semibold text-xl text-toreno-brown leading-tight">
        {{ __('Manajemen Promosi') }}
    </h2>
</x-slot>

<div class="py-12 bg-toreno-light min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-toreno-brown/10">
            <div class="p-6 sm:p-8 bg-white border-b border-gray-200">
                
                @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 shadow-sm" role="alert">
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
                @endif

                <button wire:click="create()" class="bg-toreno-brown hover:bg-toreno-accent text-white font-bold py-2 px-4 rounded mb-4 shadow-sm">
                    Tambah Promosi Baru
                </button>

                @if($isModalOpen)
                <div class="fixed z-50 inset-0 overflow-y-auto ease-out duration-400">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog">
                            <form wire:submit.prevent="store">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <h3 class="text-lg font-medium text-toreno-brown mb-4">{{ $promotion_id ? 'Edit Promosi' : 'Tambah Promosi Baru' }}</h3>
                                    
                                    <div class="mb-4">
                                        <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Judul (Opsional):</label>
                                        <input type="text" class="shadow-sm border-gray-300 focus:border-toreno-brown focus:ring focus:ring-toreno-brown/50 rounded-md w-full" id="title" wire:model="title" placeholder="Contoh: Promo Ramadhan">
                                        @error('title') <span class="text-red-500 text-xs italic">{{ $message }}</span>@enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Gambar (Disarankan rasio 16:9):</label>
                                        <input type="file" class="shadow-sm border-gray-300 focus:border-toreno-brown focus:ring focus:ring-toreno-brown/50 rounded-md w-full" id="image" wire:model="image">
                                        @error('image') <span class="text-red-500 text-xs italic">{{ $message }}</span>@enderror
                                        
                                        <div wire:loading wire:target="image" class="text-sm text-gray-500 mt-2">Mengunggah gambar...</div>
                                        @if ($image)
                                            <div class="mt-2 text-sm text-green-600 font-semibold">Gambar siap diunggah.</div>
                                        @endif
                                    </div>

                                    <div class="mb-4">
                                        <label class="flex items-center">
                                            <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-toreno-brown shadow-sm focus:border-toreno-brown focus:ring focus:ring-toreno-brown/50">
                                            <span class="ml-2 text-gray-700 text-sm font-bold">Aktif (Tampilkan di Katalog)</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                        <button type="submit" wire:loading.attr="disabled" wire:target="store" class="inline-flex justify-center items-center w-full rounded-md border border-transparent px-4 py-2 bg-toreno-brown text-base leading-6 font-medium text-white shadow-sm hover:bg-toreno-accent transition sm:text-sm sm:leading-5 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <svg wire:loading wire:target="store" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span wire:loading.remove wire:target="store">Simpan</span>
                                            <span wire:loading wire:target="store">Menyimpan...</span>
                                        </button>
                                    </span>
                                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                        <button type="button" wire:click="closeModal()" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 transition sm:text-sm sm:leading-5">
                                            Batal
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

                <div class="overflow-x-auto">
                    <p class="text-sm text-gray-500 mb-4 italic">Atur urutan promosi dengan men-drag (geser) ikon garis-garis pada baris ke atas atau ke bawah.</p>
                    <table class="table-auto w-full mt-4 text-left border-collapse">
                        <thead>
                            <tr class="bg-toreno-cream text-toreno-brown">
                                <th class="px-4 py-3 border-b-2 border-toreno-brown/20 w-24 text-center">Urutan</th>
                                <th class="px-4 py-3 border-b-2 border-toreno-brown/20 w-32">Gambar</th>
                                <th class="px-4 py-3 border-b-2 border-toreno-brown/20">Judul</th>
                                <th class="px-4 py-3 border-b-2 border-toreno-brown/20 text-center">Status</th>
                                <th class="px-4 py-3 border-b-2 border-toreno-brown/20 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="sortable-promotions">
                            @foreach($promotions as $index => $promo)
                            <tr wire:key="promo-{{ $promo->id }}" class="border-b hover:bg-gray-50 cursor-grab active:cursor-grabbing group bg-white" data-id="{{ $promo->id }}">
                                <td class="px-4 py-3 text-center text-gray-500 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-toreno-brown transition" fill="currentColor" viewBox="0 0 24 24"><path d="M3 15h18v-2H3v2zm0 4h18v-2H3v2zm0-8h18V9H3v2zm0-6v2h18V5H3z"/></svg>
                                    <span class="sort-index font-bold">{{ $index + 1 }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="w-24 h-14 bg-gray-100 rounded overflow-hidden border border-gray-200">
                                        @if($promo->image_url)
                                            <img src="{{ $promo->image_url }}" alt="Promo" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">No Img</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 font-medium">{{ $promo->title ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <button wire:click="toggleActive('{{ $promo->id }}')" class="px-3 py-1 rounded-full text-xs font-bold transition {{ $promo->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                        {{ $promo->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </td>
                                <td class="px-4 py-3 flex space-x-2 justify-center">
                                    <button wire:click="edit('{{ $promo->id }}')" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded shadow-sm text-sm transition">Edit</button>
                                    <button wire:click="delete('{{ $promo->id }}')" onclick="return confirm('Yakin ingin menghapus promosi ini?')" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-3 rounded shadow-sm text-sm transition">Hapus</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($promotions->isEmpty())
                    <div class="text-center py-8 text-gray-500">
                        Belum ada promosi yang ditambahkan.
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- SortableJS CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
    <style>
        .sortable-ghost { opacity: 0.4; background-color: #f3f4f6; }
        .sortable-chosen { background-color: #f0fdf4; }
        .sortable-drag { background-color: #ffffff; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); }
        .cursor-grab { cursor: grab; }
        .cursor-grab:active { cursor: grabbing; }
    </style>
    <script>
        document.addEventListener('livewire:navigated', () => { initSortable(); });
        document.addEventListener('DOMContentLoaded', () => { initSortable(); });

        function initSortable() {
            const el = document.getElementById('sortable-promotions');
            if (!el || el._sortableInstance) return;

            el._sortableInstance = new Sortable(el, {
                animation: 200,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                handle: '.cursor-grab',
                helper: function(e, ui) {
                    ui.children().each(function() { $(this).width($(this).width()); });
                    return ui;
                },
                onStart: function (evt) {
                    const tr = evt.item;
                    const tds = tr.querySelectorAll('td');
                    tds.forEach(td => { td.style.width = td.offsetWidth + 'px'; });
                },
                onEnd: function (evt) {
                    const tr = evt.item;
                    const tds = tr.querySelectorAll('td');
                    tds.forEach(td => { td.style.width = ''; });

                    const orderedIds = [...el.children].map(item => item.dataset.id);
                    @this.call('updateOrder', orderedIds);

                    [...el.children].forEach((item, index) => {
                        const badge = item.querySelector('.sort-index');
                        if (badge) badge.textContent = index + 1;
                    });
                }
            });
        }
    </script>
</div>
