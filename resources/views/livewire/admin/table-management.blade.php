<x-slot name="header">
    <h2 class="font-semibold text-xl text-toreno-brown leading-tight">
        {{ __('Manajemen Meja & QR Code') }}
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
                    Tambah Meja Baru
                </button>

                @if($isModalOpen)
                    <div class="fixed z-50 inset-0 overflow-y-auto ease-out duration-400">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 transition-opacity">
                                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                            </div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
                            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                                <form>
                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <div class="">
                                            <h3 class="text-lg font-medium text-toreno-brown mb-4">{{ $table_id ? 'Edit Meja' : 'Tambah Meja' }}</h3>
                                            <div class="mb-4">
                                                <label for="table_number" class="block text-gray-700 text-sm font-bold mb-2">Nomor/Nama Meja:</label>
                                                <input type="text" class="shadow-sm border-gray-300 focus:border-toreno-accent focus:ring focus:ring-toreno-accent focus:ring-opacity-50 rounded-md w-full" id="table_number" placeholder="Contoh: Meja 1, VIP A" wire:model="table_number">
                                                @error('table_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                            <button wire:click.prevent="store()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-toreno-brown text-base leading-6 font-medium text-white shadow-sm hover:bg-toreno-accent focus:outline-none focus:border-toreno-accent transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                                Simpan
                                            </button>
                                        </span>
                                        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                            <button wire:click="closeModal()" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-gray-300 transition ease-in-out duration-150 sm:text-sm sm:leading-5">
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
                                <th class="px-4 py-3 border-b-2 border-toreno-brown/20">Nomor Meja</th>
                                <th class="px-4 py-3 border-b-2 border-toreno-brown/20">Link QR (Hash)</th>
                                <th class="px-4 py-3 border-b-2 border-toreno-brown/20 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tables as $table)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 font-semibold">{{ $table->table_number }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    <a href="{{ url('/t/' . $table->qr_code_hash) }}" target="_blank" class="text-blue-600 hover:underline">/t/{{ $table->qr_code_hash }}</a>
                                </td>
                                <td class="px-4 py-3 flex space-x-2 justify-center">
                                    <button onclick="generateAndDownloadQR('{{ url('/t/' . $table->qr_code_hash) }}', 'Meja-{{ $table->table_number }}')" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-1 px-3 rounded shadow-sm text-sm" title="Download QR Code">QR Code</button>
                                    <button wire:click="edit('{{ $table->id }}')" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded shadow-sm text-sm">Edit</button>
                                    <button wire:click="delete('{{ $table->id }}')" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-3 rounded shadow-sm text-sm">Hapus</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($tables->isEmpty())
                    <div class="text-center py-8 text-gray-500">
                        Belum ada meja yang ditambahkan.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- QRious Library for QR Code Generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <script>
        function generateAndDownloadQR(url, tableName) {
            // Clean up the table name to be safe for filename
            const cleanName = tableName.replace(/[^a-z0-9]/gi, '_').toLowerCase();
            
            // Create a temporary canvas
            const canvas = document.createElement('canvas');
            
            // Generate QR Code
            new QRious({
                element: canvas,
                value: url,
                size: 800, // High resolution for printing
                level: 'H', // High error correction
                padding: 40 // Add some padding around the QR code
            });
            
            // Create a temporary link to download
            const link = document.createElement('a');
            link.download = `qrcode_${cleanName}.png`;
            link.href = canvas.toDataURL('image/png');
            
            // Trigger download
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</div>
