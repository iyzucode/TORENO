<x-slot name="header">
    <h2 class="font-semibold text-xl text-toreno-brown leading-tight">
        {{ __('Manajemen Menu') }}
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

                <button wire:click="create()" class="bg-toreno-brown hover:bg-toreno-accent text-white font-bold py-2 px-4 rounded mb-4 shadow-sm">
                    Tambah Menu Baru
                </button>

                @if($isModalOpen)
                    @include('livewire.admin.menu-modal')
                @endif

                <div class="overflow-x-auto">
                    <p class="text-sm text-gray-500 mb-4 italic">Atur urutan menu dalam kategori dengan men-drag (geser) ikon garis-garis pada baris ke atas atau ke bawah.</p>
                    <table class="table-auto w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-toreno-cream text-toreno-brown">
                                <th class="px-4 py-3 border-b-2 border-toreno-brown/20 w-24 text-center">Urutan</th>
                                <th class="px-4 py-3 border-b-2 border-toreno-brown/20">Nama</th>
                                <th class="px-4 py-3 border-b-2 border-toreno-brown/20">Kategori</th>
                                <th class="px-4 py-3 border-b-2 border-toreno-brown/20">Harga</th>
                                <th class="px-4 py-3 border-b-2 border-toreno-brown/20 text-center">Status</th>
                                <th class="px-4 py-3 border-b-2 border-toreno-brown/20 text-center">Aksi</th>
                            </tr>
                        </thead>
                        @foreach($menusByCategory as $categoryName => $menus)
                        <tbody>
                            <tr class="bg-gray-100/50">
                                <td colspan="6" class="px-4 py-3 font-bold text-toreno-brown uppercase tracking-wider text-sm border-b">
                                    Kategori: {{ $categoryName }}
                                </td>
                            </tr>
                        </tbody>
                        <tbody class="sortable-menus">
                            @foreach($menus as $index => $menu)
                            <tr wire:key="admin-menu-{{ $menu->id }}" class="border-b hover:bg-gray-50 cursor-grab active:cursor-grabbing group bg-white" data-id="{{ $menu->id }}">
                                <td class="px-4 py-3 text-center text-gray-500 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-toreno-brown transition" fill="currentColor" viewBox="0 0 24 24"><path d="M3 15h18v-2H3v2zm0 4h18v-2H3v2zm0-8h18V9H3v2zm0-6v2h18V5H3z"/></svg>
                                    <span class="sort-index font-bold">{{ $index + 1 }}</span>
                                </td>
                                <td class="px-4 py-3 font-medium">{{ $menu->name }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $menu->category->name ?? '-' }}</td>
                                <td class="px-4 py-3">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <button wire:click="toggleAvailability('{{ $menu->id }}')" class="px-3 py-1 rounded-full text-xs font-bold transition {{ $menu->is_available ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                        {{ $menu->is_available ? 'Tersedia' : 'Sold Out' }}
                                    </button>
                                </td>
                                <td class="px-4 py-3 flex space-x-2 justify-center">
                                    <button wire:click="edit('{{ $menu->id }}')" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded shadow-sm text-sm transition">Edit</button>
                                    <button wire:click="delete('{{ $menu->id }}')" onclick="return confirm('Yakin ingin menghapus menu ini?')" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-3 rounded shadow-sm text-sm transition">Hapus</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        @endforeach
                    </table>
                </div>
                @if($menusByCategory->isEmpty())
                    <div class="text-center py-8 text-gray-500">
                        Belum ada menu yang ditambahkan.
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- SortableJS CDN & Styling --}}
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
            const containers = document.querySelectorAll('.sortable-menus');
            containers.forEach(el => {
                if (el._sortableInstance) return;
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
                        @this.call('updateMenuOrder', orderedIds);

                        [...el.children].forEach((item, index) => {
                            const badge = item.querySelector('.sort-index');
                            if (badge) badge.textContent = index + 1;
                        });
                    }
                });
            });
        }
    </script>
</div>
