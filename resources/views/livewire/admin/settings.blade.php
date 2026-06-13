<div>
    <x-slot name="header">
    <h2 class="font-semibold text-xl text-toreno-brown leading-tight">
        {{ __('Pengaturan Restoran') }}
    </h2>
</x-slot>

<div class="py-12 bg-toreno-light min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg max-w-2xl">
            <div class="p-6 text-gray-900">
                @if (session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif

                <form wire:submit.prevent="save">
                    <h3 class="text-lg font-medium text-toreno-brown mb-4 border-b pb-2">Biaya & Pajak</h3>
                    
                    <div class="mb-4">
                        <label for="tax_rate" class="block text-gray-700 text-sm font-bold mb-2">Pajak / PPn (%)</label>
                        <div class="flex items-center">
                            <input type="number" step="0.01" class="shadow-sm border-gray-300 focus:border-toreno-accent focus:ring focus:ring-toreno-accent focus:ring-opacity-50 rounded-l-md w-full" id="tax_rate" wire:model="tax_rate">
                            <span class="bg-gray-100 border border-l-0 border-gray-300 px-3 py-2 rounded-r-md text-gray-500">%</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Pajak akan dihitung berdasarkan subtotal murni pesanan.</p>
                        @error('tax_rate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Biaya Layanan</label>
                        <div class="flex gap-4 mb-3">
                            <label class="flex items-center">
                                <input type="radio" wire:model.live="service_charge_type" value="percentage" class="text-toreno-accent focus:ring-toreno-accent mr-2">
                                <span class="text-sm">Persentase (%)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" wire:model.live="service_charge_type" value="fixed" class="text-toreno-accent focus:ring-toreno-accent mr-2">
                                <span class="text-sm">Nominal (Rp)</span>
                            </label>
                        </div>

                        <div class="flex items-center">
                            @if($service_charge_type === 'percentage')
                                <input type="number" step="0.01" class="shadow-sm border-gray-300 focus:border-toreno-accent focus:ring focus:ring-toreno-accent focus:ring-opacity-50 rounded-l-md w-full" id="service_charge_rate" wire:model="service_charge_rate">
                                <span class="bg-gray-100 border border-l-0 border-gray-300 px-3 py-2 rounded-r-md text-gray-500">%</span>
                            @else
                                <span class="bg-gray-100 border border-r-0 border-gray-300 px-3 py-2 rounded-l-md text-gray-500">Rp</span>
                                <input type="number" step="1" class="shadow-sm border-gray-300 focus:border-toreno-accent focus:ring focus:ring-toreno-accent focus:ring-opacity-50 rounded-r-md w-full" id="service_charge_rate" wire:model="service_charge_rate">
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Biaya layanan akan ditambahkan ke total akhir pembayaran.</p>
                        @error('service_charge_rate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                        @error('service_charge_type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" wire:loading.attr="disabled" class="bg-toreno-brown hover:bg-toreno-accent text-white font-bold py-2 px-6 rounded shadow-sm flex items-center transition disabled:opacity-50">
                            <span wire:loading.remove wire:target="save">Simpan Pengaturan</span>
                            <span wire:loading wire:target="save">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
