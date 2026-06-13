<x-slot name="header">
    <h2 class="font-semibold text-xl text-toreno-brown leading-tight">
        {{ __('Dashboard Owner') }}
    </h2>
</x-slot>

<div class="py-12 bg-gray-50 min-h-screen font-sans">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
        
        <!-- Filter Section -->
        <div class="bg-white/70 backdrop-blur-lg border border-white/50 p-6 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)]">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-toreno-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Filter Data Penjualan
            </h3>
            
            <form wire:submit.prevent="filter" class="flex flex-col md:flex-row md:items-end gap-6">
                <div class="flex-1">
                    <label for="startDate" class="block text-sm font-semibold text-gray-600 mb-2">Waktu Mulai</label>
                    <input type="datetime-local" id="startDate" wire:model="startDate" lang="id"
                           class="w-full rounded-xl border-gray-200 bg-gray-50/50 shadow-inner focus:border-toreno-accent focus:ring focus:ring-toreno-accent/30 transition duration-200 py-2.5 px-4">
                    @error('startDate') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div class="flex-1">
                    <label for="endDate" class="block text-sm font-semibold text-gray-600 mb-2">Waktu Selesai</label>
                    <input type="datetime-local" id="endDate" wire:model="endDate" lang="id"
                           class="w-full rounded-xl border-gray-200 bg-gray-50/50 shadow-inner focus:border-toreno-accent focus:ring focus:ring-toreno-accent/30 transition duration-200 py-2.5 px-4">
                    @error('endDate') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <button type="submit" wire:loading.attr="disabled"
                            class="w-full md:w-auto px-8 py-3 bg-gradient-to-r from-toreno-brown to-[#704f36] text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-toreno-brown active:scale-95 flex items-center justify-center">
                        <span wire:loading.remove>Terapkan Filter</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Total Revenue Card -->
            <div class="relative overflow-hidden rounded-3xl p-8 shadow-xl text-white group transition-transform duration-300 hover:scale-[1.02]" style="background: linear-gradient(to bottom right, #8E5E3B, #5a3b25);">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative z-10">
                    <p class="text-white/80 text-sm font-medium tracking-wider uppercase mb-1">Total Pendapatan</p>
                    <h3 class="text-4xl font-extrabold tracking-tight drop-shadow-md">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </h3>
                    <p class="mt-4 text-xs text-white/80 bg-black/30 inline-block px-3 py-1 rounded-full backdrop-blur-sm">
                        Pesanan Selesai (Completed)
                    </p>
                </div>
                <!-- Icon -->
                <div class="absolute bottom-6 right-6 text-white/20 group-hover:text-white/30 transition-colors">
                    <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <!-- Total Orders Card -->
            <div class="relative overflow-hidden rounded-3xl p-8 shadow-xl text-white group transition-transform duration-300 hover:scale-[1.02]" style="background: linear-gradient(to bottom right, #c99a6c, #a67c52);">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative z-10">
                    <p class="text-white/80 text-sm font-medium tracking-wider uppercase mb-1">Total Pesanan</p>
                    <h3 class="text-4xl font-extrabold tracking-tight drop-shadow-md">
                        {{ number_format($totalOrdersCount, 0, ',', '.') }} <span class="text-xl font-medium text-white/80">order</span>
                    </h3>
                    <p class="mt-4 text-xs text-white/80 bg-black/30 inline-block px-3 py-1 rounded-full backdrop-blur-sm">
                        Dalam rentang waktu terpilih
                    </p>
                </div>
                <!-- Icon -->
                <div class="absolute bottom-6 right-6 text-white/20 group-hover:text-white/30 transition-colors">
                    <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
            </div>
        </div>

        <!-- Data Analytics Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl p-5 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100 flex items-center">
                <div class="p-3 bg-blue-50 text-blue-500 rounded-xl mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Rata-rata Nilai Pesanan</p>
                    <p class="text-xl font-bold text-gray-800">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</p>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-5 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100 flex items-center">
                <div class="p-3 bg-green-50 text-green-500 rounded-xl mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Total Item Terjual</p>
                    <p class="text-xl font-bold text-gray-800">{{ number_format($totalItemsSold, 0, ',', '.') }} <span class="text-sm font-medium text-gray-500">item</span></p>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-gray-100 flex items-center">
                <div class="p-3 bg-purple-50 text-purple-500 rounded-xl mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Rata-rata Item per Pesanan</p>
                    <p class="text-xl font-bold text-gray-800">{{ number_format($averageItemsPerOrder, 1, ',', '.') }} <span class="text-sm font-medium text-gray-500">item</span></p>
                </div>
            </div>
        </div>

        <!-- Sales History Table -->
        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden border border-gray-100" x-data="{ expandAll: false }">
            <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-gradient-to-r from-gray-50 to-white">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Riwayat Penjualan (Pesanan)
                </h3>
                
                <!-- Toggle Switch for Expand All -->
                <label class="flex items-center cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" class="sr-only" x-model="expandAll">
                        <div class="block bg-gray-200 w-10 h-6 rounded-full transition-colors duration-300" :class="{ 'bg-toreno-accent': expandAll }"></div>
                        <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform duration-300" :class="{ 'transform translate-x-4': expandAll }"></div>
                    </div>
                    <div class="ml-3 text-sm font-semibold text-gray-600 select-none" x-text="expandAll ? 'Sembunyikan Detail' : 'Tampilkan Detail'">
                        Tampilkan Detail
                    </div>
                </label>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4">Nomor Meja</th>
                            <th class="px-6 py-4">Nama Pemesan</th>
                            <th class="px-6 py-4">Metode Pembayaran</th>
                            <th class="px-6 py-4 text-right">Total Pembayaran</th>
                        </tr>
                    </thead>
                    @forelse($ordersList as $order)
                    <tbody x-data="{ expanded: false }" class="divide-y divide-gray-50 border-b border-gray-100">
                        <tr @click="expanded = !expanded" class="cursor-pointer hover:bg-gray-50/80 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400 transition-transform duration-300" :class="{'rotate-90': expandAll || expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-md text-xs font-bold">{{ $order->table ? $order->table->table_number : '-' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">
                                <div>{{ $order->customer_name ?: 'Tidak Diketahui' }}</div>
                                @if($order->customer_phone)
                                    <div class="text-xs text-gray-500 font-normal mt-0.5">{{ $order->customer_phone }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded text-xs font-semibold uppercase tracking-wider border border-indigo-100">
                                    {{ $order->payment_method }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-toreno-brown group-hover:text-toreno-accent transition-colors">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                        <!-- Details Row -->
                        <tr x-show="expandAll || expanded" x-collapse x-cloak>
                            <td colspan="5" class="px-0 py-0">
                                <div class="bg-slate-50/80 border-t border-gray-100 p-4">
                                    <div class="ml-10 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                                        <table class="w-full text-sm text-left">
                                            <thead class="bg-gray-50/50 text-gray-500 font-semibold border-b border-gray-100">
                                                <tr>
                                                    <th class="px-4 py-2">Item</th>
                                                    <th class="px-4 py-2 text-center">Jumlah</th>
                                                    <th class="px-4 py-2 text-right">Harga</th>
                                                    <th class="px-4 py-2 text-right">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-50">
                                                @foreach($order->items as $item)
                                                <tr>
                                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $item->menu_name ?: 'Menu Tidak Diketahui' }}</td>
                                                    <td class="px-4 py-3 text-center">
                                                        <span class="inline-flex items-center justify-center bg-gray-100 text-gray-600 rounded-md px-2 py-1 text-xs font-bold">{{ $item->quantity }}</span>
                                                    </td>
                                                    <td class="px-4 py-3 text-right text-gray-500">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                                    <td class="px-4 py-3 text-right font-semibold text-gray-700">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @if($order->notes)
                                        <div class="px-4 py-3 bg-yellow-50/50 border-t border-yellow-100 text-yellow-800 text-xs flex items-start">
                                            <svg class="w-4 h-4 mr-2 text-yellow-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span class="font-medium">Catatan:</span> <span class="ml-1 text-yellow-700 italic">{{ $order->notes }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    @empty
                    <tbody>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <p class="text-base font-medium">Belum ada transaksi</p>
                                <p class="text-sm">Tidak ada pesanan selesai pada rentang waktu ini.</p>
                            </td>
                        </tr>
                    </tbody>
                    @endforelse
                    @if($ordersList->count() > 0)
                    <tfoot class="bg-gray-100 border-t-2 border-gray-200">
                        <tr>
                            <td colspan="4" class="px-6 py-5 text-right text-sm font-extrabold text-gray-800 uppercase tracking-widest">
                                TOTAL PEMBAYARAN KESELURUHAN
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-right text-xl font-black text-toreno-brown drop-shadow-sm">
                                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>

        <!-- Top Selling Items Table -->
        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden border border-gray-100">
            <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-gradient-to-r from-gray-50 to-white">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    Daftar Menu Terlaris
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                            <th class="px-6 py-4">Peringkat</th>
                            <th class="px-6 py-4">Nama Menu</th>
                            <th class="px-6 py-4 text-center">Terjual</th>
                            <th class="px-6 py-4 text-right">Estimasi Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($topItems as $index => $item)
                        <tr class="hover:bg-gray-50/80 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($index == 0)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 font-bold shadow-sm">1</span>
                                @elseif($index == 1)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-600 font-bold shadow-sm">2</span>
                                @elseif($index == 2)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-orange-100 text-orange-600 font-bold shadow-sm">3</span>
                                @else
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-50 text-gray-400 font-semibold">{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">
                                {{ $item->menu_name ?: 'Menu Tidak Diketahui' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 bg-green-50 text-green-700 rounded-full text-sm font-semibold border border-green-100">
                                    {{ $item->total_sold }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-medium text-gray-600">
                                Rp {{ number_format($item->total_revenue, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="text-base font-medium">Belum ada data penjualan</p>
                                <p class="text-sm">Tidak ada pesanan selesai pada rentang waktu ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>
