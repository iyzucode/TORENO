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
                    <table class="table-auto w-full mt-4 text-left border-collapse">
                        <thead>
                            <tr class="bg-toreno-cream text-toreno-brown">
                                <th class="px-4 py-3 border-b-2 border-toreno-brown/20">Nama</th>
                                <th class="px-4 py-3 border-b-2 border-toreno-brown/20">Kategori</th>
                                <th class="px-4 py-3 border-b-2 border-toreno-brown/20">Harga</th>
                                <th class="px-4 py-3 border-b-2 border-toreno-brown/20 text-center">Status</th>
                                <th class="px-4 py-3 border-b-2 border-toreno-brown/20 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($menus as $menu)
                            <tr wire:key="admin-menu-{{ $menu->id }}" class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $menu->name }}</td>
                                <td class="px-4 py-3">{{ $menu->category->name ?? '-' }}</td>
                                <td class="px-4 py-3">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <button wire:click="toggleAvailability('{{ $menu->id }}')" class="px-3 py-1 rounded-full text-xs font-bold {{ $menu->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $menu->is_available ? 'Tersedia' : 'Sold Out' }}
                                    </button>
                                </td>
                                <td class="px-4 py-3 flex space-x-2 justify-center">
                                    <button wire:click="edit('{{ $menu->id }}')" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded shadow-sm text-sm">Edit</button>
                                    <button wire:click="delete('{{ $menu->id }}')" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-3 rounded shadow-sm text-sm">Hapus</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($menus->isEmpty())
                    <div class="text-center py-8 text-gray-500">
                        Belum ada menu yang ditambahkan.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
