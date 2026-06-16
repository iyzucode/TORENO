<x-slot name="header">
    <h2 class="font-semibold text-xl text-toreno-brown leading-tight">
        {{ __('Admin Dashboard') }}
    </h2>
</x-slot>

<div class="py-12 bg-toreno-light min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Quick Access Buttons -->
        <div class="mb-8 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Akses Cepat</h3>
            <div class="flex flex-wrap gap-4">
                <a wire:navigate href="{{ route('admin.menus') }}" class="px-5 py-2.5 bg-toreno-brown text-white font-semibold rounded-xl hover:bg-toreno-accent hover:-translate-y-0.5 transition-all shadow-md flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Kelola Menu
                </a>
                <a wire:navigate href="{{ route('admin.categories') }}" class="px-5 py-2.5 bg-white border-2 border-toreno-brown text-toreno-brown font-semibold rounded-xl hover:bg-toreno-cream hover:-translate-y-0.5 transition-all shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    Kategori Menu
                </a>
                <a wire:navigate href="{{ route('admin.promotions') }}" class="px-5 py-2.5 bg-white border-2 border-toreno-accent text-toreno-accent font-semibold rounded-xl hover:bg-red-50 hover:-translate-y-0.5 transition-all shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 11V9a2 2 0 00-2-2m2 4v4a2 2 0 104 0v-1m-4-3H9m2 0h4m6 1a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Promosi
                </a>
                <a wire:navigate href="{{ route('admin.users') }}" class="px-5 py-2.5 bg-gray-800 text-white font-semibold rounded-xl hover:bg-gray-700 hover:-translate-y-0.5 transition-all shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Pengguna
                </a>
            </div>
        </div>

        <!-- Metric Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Active Menus -->
            <div class="bg-gradient-to-br from-white to-orange-50 overflow-hidden shadow-sm rounded-2xl border border-orange-100 flex items-center p-6">
                <div class="p-3 bg-orange-100 text-orange-600 rounded-xl mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <div>
                    <div class="text-sm font-semibold text-gray-500">Total Menu Aktif</div>
                    <div class="text-3xl font-bold text-gray-900">{{ $totalActiveMenus }}</div>
                </div>
            </div>

            <!-- Total Promotions -->
            <div class="bg-gradient-to-br from-white to-red-50 overflow-hidden shadow-sm rounded-2xl border border-red-100 flex items-center p-6">
                <div class="p-3 bg-red-100 text-red-600 rounded-xl mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                </div>
                <div>
                    <div class="text-sm font-semibold text-gray-500">Promosi Aktif</div>
                    <div class="text-3xl font-bold text-gray-900">{{ $totalPromotions }}</div>
                </div>
            </div>

            <!-- Total Tables -->
            <div class="bg-gradient-to-br from-white to-yellow-50 overflow-hidden shadow-sm rounded-2xl border border-yellow-100 flex items-center p-6">
                <div class="p-3 bg-yellow-100 text-yellow-700 rounded-xl mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                </div>
                <div>
                    <div class="text-sm font-semibold text-gray-500">Total Meja</div>
                    <div class="text-3xl font-bold text-gray-900">{{ $totalTables }}</div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="bg-gradient-to-br from-white to-blue-50 overflow-hidden shadow-sm rounded-2xl border border-blue-100 flex items-center p-6">
                <div class="p-3 bg-blue-100 text-blue-600 rounded-xl mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <div class="text-sm font-semibold text-gray-500">Pengguna Sistem</div>
                    <div class="text-3xl font-bold text-gray-900">{{ $totalUsers }}</div>
                </div>
            </div>
        </div>

        <!-- Detailed Lists Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Out of Stock Menus -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Perhatian: Menu Habis
                    </h3>
                    <span class="bg-red-100 text-red-800 text-xs font-bold px-2.5 py-0.5 rounded-full">{{ $outOfStockMenus->count() }}</span>
                </div>
                <div class="p-0">
                    @if($outOfStockMenus->count() > 0)
                        <ul class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                            @foreach($outOfStockMenus as $menu)
                                <li class="p-4 hover:bg-gray-50 flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        @if($menu->image_url)
                                            <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                                        @else
                                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $menu->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $menu->category->name ?? 'Tanpa Kategori' }}</p>
                                        </div>
                                    </div>
                                    <a wire:navigate href="{{ route('admin.menus') }}" class="text-sm text-toreno-accent hover:underline font-medium">Ubah Status</a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="p-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-green-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="font-medium">Semua menu tersedia saat ini.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Active Promotions Overview -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-toreno-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 11V9a2 2 0 00-2-2m2 4v4a2 2 0 104 0v-1m-4-3H9m2 0h4m6 1a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Promosi Berjalan
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        
                        <!-- Pop-up Status -->
                        <div>
                            <h4 class="text-xs font-bold text-gray-500 uppercase mb-3">Pop-up Katalog</h4>
                            @if($activePopup)
                                <div class="flex items-center gap-3 p-3 bg-green-50 border border-green-100 rounded-xl">
                                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                    <span class="text-sm font-semibold text-green-800">Pop-up Aktif Menampilkan Gambar</span>
                                </div>
                            @else
                                <div class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-500">
                                    <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                                    <span class="text-sm">Tidak ada Pop-up aktif</span>
                                </div>
                            @endif
                        </div>

                        <!-- Slider Promotions -->
                        <div>
                            <h4 class="text-xs font-bold text-gray-500 uppercase mb-3">Slider Banner ({{ $activeSliders->count() }})</h4>
                            @if($activeSliders->count() > 0)
                                <div class="flex gap-2 overflow-x-auto pb-2">
                                    @foreach($activeSliders as $slider)
                                        <div class="flex-shrink-0 relative w-24 h-16 rounded-lg overflow-hidden border border-gray-200">
                                            <img src="{{ $slider->image_url }}" alt="Promo" class="w-full h-full object-cover">
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 italic">Tidak ada slider promosi yang tayang.</p>
                            @endif
                        </div>

                        <!-- Promo Codes -->
                        <div>
                            <h4 class="text-xs font-bold text-gray-500 uppercase mb-3">Kode Voucher ({{ $activePromoCodes->count() }})</h4>
                            @if($activePromoCodes->count() > 0)
                                <ul class="space-y-2">
                                    @foreach($activePromoCodes as $code)
                                        <li class="flex justify-between items-center bg-gray-50 px-3 py-2 rounded-lg border border-gray-100">
                                            <span class="font-mono font-bold text-toreno-accent">{{ $code->code }}</span>
                                            <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded">Dipakai {{ $code->used_count }}x</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-sm text-gray-500 italic">Tidak ada kode voucher aktif.</p>
                            @endif
                        </div>

                    </div>
                    <div class="mt-6 text-center">
                        <a wire:navigate href="{{ route('admin.promotions') }}" class="text-sm text-toreno-brown hover:text-toreno-accent font-semibold transition">Kelola Semua Promosi &rarr;</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
