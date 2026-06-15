<x-slot name="header">
    <h2 class="font-semibold text-xl text-toreno-brown leading-tight">
        {{ __('Kelola User') }}
    </h2>
</x-slot>

<div class="py-12 bg-toreno-light min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-toreno-brown">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Daftar User</h3>
                    <div class="flex gap-4">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama/email..." class="border-gray-300 focus:border-toreno-accent focus:ring-toreno-accent rounded-md shadow-sm text-sm">
                        <button wire:click="create" class="bg-toreno-brown hover:bg-toreno-accent text-white font-bold py-2 px-4 rounded transition flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah User
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Nama
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Email
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Role
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap font-medium">{{ $user->name }}</p>
                                    </td>
                                    <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                        <p class="text-gray-600 whitespace-no-wrap">{{ $user->email }}</p>
                                    </td>
                                    <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                        @php
                                            $roleColor = match($user->role) {
                                                'owner' => 'bg-purple-100 text-purple-800',
                                                'admin' => 'bg-blue-100 text-blue-800',
                                                'cashier' => 'bg-green-100 text-green-800',
                                                'kitchen' => 'bg-orange-100 text-orange-800',
                                                default => 'bg-gray-100 text-gray-800',
                                            };
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $roleColor }}">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-center">
                                        <div class="flex justify-center items-center gap-3">
                                            <button wire:click="generateToken('{{ $user->id }}')" class="text-indigo-600 hover:text-indigo-900 flex items-center" title="Generate Reset Password Token">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                            </button>
                                            <button wire:click="edit('{{ $user->id }}')" class="text-blue-600 hover:text-blue-900" title="Edit User">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            <button onclick="confirm('Apakah Anda yakin ingin menghapus user ini?') || event.stopImmediatePropagation()" wire:click="delete('{{ $user->id }}')" class="text-red-600 hover:text-red-900" title="Hapus User">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-8 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                                        Tidak ada data user.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Create/Edit -->
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="store">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                {{ $user_id ? 'Edit User' : 'Tambah User' }}
                            </h3>
                            <div class="mb-4">
                                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap:</label>
                                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-toreno-brown focus:ring-0 @error('name') border-red-500 @enderror" id="name" wire:model="name" required>
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                                <input type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-toreno-brown focus:ring-0 @error('email') border-red-500 @enderror" id="email" wire:model="email" required>
                                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Role:</label>
                                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-toreno-brown focus:ring-0 @error('role') border-red-500 @enderror" id="role" wire:model="role" required>
                                    <option value="owner">Owner</option>
                                    <option value="admin">Admin</option>
                                    <option value="cashier">Cashier</option>
                                    <option value="kitchen">Kitchen</option>
                                </select>
                                @error('role') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>
                            @if(!$user_id)
                            <div class="mb-4">
                                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
                                <input type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-toreno-brown focus:ring-0 @error('password') border-red-500 @enderror" id="password" wire:model="password" required>
                                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>
                            @endif
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-toreno-brown text-base font-medium text-white hover:bg-toreno-accent focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Simpan
                            </button>
                            <button type="button" wire:click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Token Reset -->
    @if($isTokenModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="token-modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeTokenModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 mb-4">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        </div>
                        <h3 class="text-lg leading-6 font-bold text-gray-900 mb-2" id="token-modal-title">
                            Token Reset Password
                        </h3>
                        <p class="text-sm text-gray-500 mb-4">
                            Token untuk user <strong>{{ $tokenUser->name ?? '' }}</strong> ({{ $tokenUser->email ?? '' }}) telah di-generate. Silakan copy token di bawah ini dan berikan kepada user yang bersangkutan.
                        </p>
                        
                        <div class="bg-gray-50 border border-gray-200 p-3 rounded-lg flex items-center gap-2 mb-2">
                            <input type="text" readonly value="{{ $generatedToken }}" id="resetTokenInput" class="w-full text-sm font-mono text-gray-700 bg-transparent border-none focus:ring-0">
                            <button onclick="navigator.clipboard.writeText(document.getElementById('resetTokenInput').value); alert('Token berhasil disalin!');" class="bg-gray-200 hover:bg-gray-300 text-gray-700 p-2 rounded-md transition" title="Copy Token">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                            </button>
                        </div>
                        <p class="text-xs text-red-500 mt-2 font-medium">⚠️ Token ini hanya berlaku selama 1 jam.</p>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="closeTokenModal" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
