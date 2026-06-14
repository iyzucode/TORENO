<x-slot name="header">
    <h2 class="font-semibold text-xl text-toreno-brown leading-tight">
        {{ __('Manajemen Kategori Menu') }}
    </h2>
</x-slot>

<div class="py-12 bg-toreno-light min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                @if (session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <button wire:click="create()" class="bg-toreno-brown hover:bg-toreno-accent text-white font-bold py-2 px-4 rounded mb-2 shadow-sm">
                    Tambah Kategori Baru
                </button>
                
                <p class="text-sm text-gray-500 mb-4 italic">Atur urutan kategori dengan men-drag (geser) ikon garis-garis pada baris ke atas atau ke bawah. Kategori paling atas akan tampil duluan di halaman menu pelanggan.</p>

                {{-- Modal --}}
                @if($isModalOpen)
                <div class="fixed z-50 inset-0 overflow-y-auto ease-out duration-400">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full" role="dialog">
                            <form>
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <h3 class="text-lg font-medium text-toreno-brown mb-4">{{ $category_id ? 'Edit Kategori' : 'Tambah Kategori Baru' }}</h3>
                                    <div class="mb-4">
                                        <label for="cat_name" class="block text-gray-700 text-sm font-bold mb-2">Nama Kategori:</label>
                                        <input type="text" class="shadow-sm border-gray-300 focus:border-toreno-accent focus:ring focus:ring-toreno-accent focus:ring-opacity-50 rounded-md w-full" id="cat_name" placeholder="Contoh: Makanan, Minuman, Snack" wire:model="name">
                                        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                        <button wire:click.prevent="store()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-toreno-brown text-base leading-6 font-medium text-white shadow-sm hover:bg-toreno-accent transition sm:text-sm sm:leading-5">
                                            Simpan
                                        </button>
                                    </span>
                                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                        <button wire:click="closeModal()" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 transition sm:text-sm sm:leading-5">
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
                    <table class="table-auto w-full mt-4 text-left border-collapse">
                        <thead>
                            <tr class="bg-toreno-cream text-toreno-brown">
                                <th class="px-4 py-3 border-b-2 border-toreno-brown/20 w-24 text-center">Urutan</th>
                                <th class="px-4 py-3 border-b-2 border-toreno-brown/20">Nama Kategori</th>
                                <th class="px-4 py-3 border-b-2 border-toreno-brown/20 text-center">Jumlah Menu</th>
                                <th class="px-4 py-3 border-b-2 border-toreno-brown/20 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="sortable-categories">
                            @foreach($categories as $index => $cat)
                            <tr class="border-b hover:bg-gray-50 cursor-grab active:cursor-grabbing group bg-white" data-id="{{ $cat->id }}" wire:key="category-{{ $cat->id }}">
                                <td class="px-4 py-3 text-center text-gray-500 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-toreno-brown transition" fill="currentColor" viewBox="0 0 24 24"><path d="M3 15h18v-2H3v2zm0 4h18v-2H3v2zm0-8h18V9H3v2zm0-6v2h18V5H3z"/></svg>
                                    <span class="sort-index font-bold">{{ $index + 1 }}</span>
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $cat->name }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs font-semibold">{{ $cat->menus_count ?? $cat->menus()->count() }}</span>
                                </td>
                                <td class="px-4 py-3 flex space-x-2 justify-center">
                                    <button wire:click="edit('{{ $cat->id }}')" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded shadow-sm text-sm">Edit</button>
                                    <button wire:click="delete('{{ $cat->id }}')" onclick="return confirm('Yakin ingin menghapus kategori ini?')" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-3 rounded shadow-sm text-sm">Hapus</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($categories->isEmpty())
                    <div class="text-center py-8 text-gray-500">
                        Belum ada kategori. Tambahkan kategori pertama Anda!
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- SortableJS CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
    <style>
        .sortable-ghost {
            opacity: 0.4;
            background-color: #f3f4f6; /* gray-100 */
        }
        .sortable-chosen {
            background-color: #f0fdf4; /* green-50, slight highlight */
        }
        .sortable-drag {
            background-color: #ffffff;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .cursor-grab {
            cursor: grab;
        }
        .cursor-grab:active {
            cursor: grabbing;
        }
    </style>
    <script>
        document.addEventListener('livewire:navigated', () => {
            initSortable();
        });

        document.addEventListener('DOMContentLoaded', () => {
            initSortable();
        });

        function initSortable() {
            const el = document.getElementById('sortable-categories');
            if (!el || el._sortableInstance) return;

            el._sortableInstance = new Sortable(el, {
                animation: 200,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                handle: '.cursor-grab',
                // To prevent table rows from collapsing when dragged:
                helper: function(e, ui) {
                    ui.children().each(function() {
                        $(this).width($(this).width());
                    });
                    return ui;
                },
                onStart: function (evt) {
                    // Fix width of td elements during drag
                    const tr = evt.item;
                    const tds = tr.querySelectorAll('td');
                    tds.forEach(td => {
                        td.style.width = td.offsetWidth + 'px';
                    });
                },
                onEnd: function (evt) {
                    // Reset td widths after drag
                    const tr = evt.item;
                    const tds = tr.querySelectorAll('td');
                    tds.forEach(td => {
                        td.style.width = '';
                    });

                    const orderedIds = [...el.children].map(item => item.dataset.id);
                    @this.call('updateOrder', orderedIds);

                    // Update badge numbers visually
                    [...el.children].forEach((item, index) => {
                        const badge = item.querySelector('.sort-index');
                        if (badge) badge.textContent = index + 1;
                    });
                }
            });
        }
    </script>
</div>
