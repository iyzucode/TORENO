<x-slot name="header">
    <h2 class="font-semibold text-xl text-toreno-brown leading-tight">
        {{ __('Kupon Diskon') }}
    </h2>
</x-slot>

<div class="py-12 bg-toreno-light min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-toreno-brown/10">
            <div class="p-6 sm:p-8 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <p class="text-sm text-gray-500">Kelola kupon potongan harga untuk pelanggan.</p>
                    </div>
                    <button wire:click="create" class="bg-toreno-brown hover:bg-toreno-accent text-white font-bold py-2 px-4 rounded shadow-sm transition active:scale-95 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Kupon
                    </button>
                </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-4">Kode</th>
                        <th scope="col" class="px-6 py-4">Digunakan</th>
                        <th scope="col" class="px-6 py-4">Tipe & Nilai</th>
                        <th scope="col" class="px-6 py-4">Maks. Potongan</th>
                        <th scope="col" class="px-6 py-4">Min. Beli</th>
                        <th scope="col" class="px-6 py-4">Masa Berlaku</th>
                        <th scope="col" class="px-6 py-4 text-center">Status</th>
                        <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promoCodes as $promo)
                        <tr class="bg-white border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-toreno-brown">
                                {{ $promo->code }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-medium text-sm">
                                {{ $promo->used_count }}{{ $promo->usage_limit ? ' / ' . $promo->usage_limit : '' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($promo->type === 'percentage')
                                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Persentase</span>
                                    <div class="mt-1 font-bold text-gray-800">{{ $promo->value }}%</div>
                                @else
                                    <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Nominal</span>
                                    <div class="mt-1 font-bold text-gray-800">Rp {{ number_format($promo->value, 0, ',', '.') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-medium text-sm">
                                @if($promo->type === 'percentage' && $promo->max_discount)
                                    Rp {{ number_format($promo->max_discount, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                Rp {{ number_format($promo->min_purchase, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <div><span class="font-semibold">Mulai:</span> {{ $promo->start_date ? $promo->start_date->format('d M Y H:i') : '-' }}</div>
                                <div><span class="font-semibold">Akhir:</span> {{ $promo->end_date ? $promo->end_date->format('d M Y H:i') : 'Tanpa batas' }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $now = now();
                                    $isExpired = $promo->end_date && $now->gt($promo->end_date);
                                    $isUpcoming = $promo->start_date && $now->lt($promo->start_date);
                                @endphp
                                
                                @if($isExpired)
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200">Kadaluarsa</span>
                                @elseif($isUpcoming)
                                    <button wire:click="toggleActive('{{ $promo->id }}')" class="px-3 py-1 rounded-full text-xs font-bold transition {{ $promo->is_active ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                        {{ $promo->is_active ? 'Belum Berlaku' : 'Nonaktif' }}
                                    </button>
                                @else
                                    <button wire:click="toggleActive('{{ $promo->id }}')" class="px-3 py-1 rounded-full text-xs font-bold transition {{ $promo->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                        {{ $promo->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center space-x-2">
                                    <button wire:click="edit('{{ $promo->id }}')" class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg transition" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button wire:click="confirmDelete('{{ $promo->id }}')" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                Belum ada kode kupon yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($promoCodes->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $promoCodes->links() }}
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    @if($isModalOpen)
    <div class="fixed z-50 inset-0 overflow-y-auto ease-out duration-400">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity flex items-center justify-center z-50">
                <div class="bg-white rounded-2xl overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full p-6 text-left">
                    <div class="flex justify-between items-center mb-5 border-b border-gray-100 pb-3">
                        <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                            {{ $promoCodeId ? 'Edit Kupon Diskon' : 'Tambah Kupon Diskon' }}
                        </h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="store">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Kode Promo <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="code" class="shadow-sm border-gray-300 focus:border-toreno-accent focus:ring focus:ring-toreno-accent focus:ring-opacity-50 rounded-xl w-full py-2 px-3 uppercase" placeholder="Contoh: HEMAT20">
                            @error('code') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tipe Diskon <span class="text-red-500">*</span></label>
                                <select wire:model.live="type" class="shadow-sm border-gray-300 focus:border-toreno-accent focus:ring focus:ring-toreno-accent focus:ring-opacity-50 rounded-xl w-full py-2 px-3 text-sm">
                                    <option value="percentage">Persentase (%)</option>
                                    <option value="fixed">Nominal Tetap (Rp)</option>
                                </select>
                                @error('type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nilai Diskon <span class="text-red-500">*</span></label>
                                <input type="number" wire:model="value" class="shadow-sm border-gray-300 focus:border-toreno-accent focus:ring focus:ring-toreno-accent focus:ring-opacity-50 rounded-xl w-full py-2 px-3" placeholder="{{ $type === 'percentage' ? 'Contoh: 20' : 'Contoh: 15000' }}">
                                @error('value') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Minimum Belanja (Rp) <span class="text-red-500">*</span></label>
                                <input type="number" wire:model="min_purchase" class="shadow-sm border-gray-300 focus:border-toreno-accent focus:ring focus:ring-toreno-accent focus:ring-opacity-50 rounded-xl w-full py-2 px-3" placeholder="0 jika tanpa min">
                                @error('min_purchase') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Maksimum Diskon (Rp)</label>
                                <input type="number" wire:model="max_discount" class="shadow-sm border-gray-300 focus:border-toreno-accent focus:ring focus:ring-toreno-accent focus:ring-opacity-50 rounded-xl w-full py-2 px-3" placeholder="Opsional" {{ $type === 'fixed' ? 'disabled bg-gray-100' : '' }}>
                                @error('max_discount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Mulai Berlaku</label>
                                <input type="datetime-local" wire:model="start_date" class="shadow-sm border-gray-300 focus:border-toreno-accent focus:ring focus:ring-toreno-accent focus:ring-opacity-50 rounded-xl w-full py-2 px-3 text-sm">
                                @error('start_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Berakhir Pada</label>
                                <input type="datetime-local" wire:model="end_date" class="shadow-sm border-gray-300 focus:border-toreno-accent focus:ring focus:ring-toreno-accent focus:ring-opacity-50 rounded-xl w-full py-2 px-3 text-sm">
                                @error('end_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Batas Penggunaan (Opsional)</label>
                            <input type="number" wire:model="usage_limit" class="shadow-sm border-gray-300 focus:border-toreno-accent focus:ring focus:ring-toreno-accent focus:ring-opacity-50 rounded-xl w-full py-2 px-3" placeholder="Kosongkan jika tanpa batas">
                            @error('usage_limit') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100 mt-6">
                            <button type="button" wire:click="closeModal" class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-toreno-brown hover:bg-toreno-accent text-white rounded-xl shadow-md transition active:scale-95">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($isDeleteModalOpen)
    <div class="fixed z-50 inset-0 overflow-y-auto ease-out duration-400">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity flex items-center justify-center z-50">
                <div class="bg-white rounded-2xl overflow-hidden shadow-xl transform transition-all sm:max-w-sm w-full p-6 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-bold text-gray-900 mb-2">Hapus Kupon?</h3>
                    <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin menghapus kupon ini? Kupon yang dihapus tidak dapat dikembalikan.</p>
                    
                    <div class="flex items-center justify-center space-x-3">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition w-full">Batal</button>
                        <button type="button" wire:click="delete" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl shadow-md transition active:scale-95 w-full">Ya, Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
            </div>
        </div>
    </div>
</div>
