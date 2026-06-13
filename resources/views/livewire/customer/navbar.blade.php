<div x-data class="fixed bottom-5 w-[calc(100%-2.5rem)] max-w-[calc(28rem-2.5rem)] left-1/2 transform -translate-x-1/2 bg-white/30 backdrop-blur-lg border border-white/40 flex justify-around py-3 z-50 shadow-[0_8px_30px_rgba(0,0,0,0.1)] rounded-3xl">
    <a wire:navigate href="{{ route('customer.catalog', ['qr_hash' => session('qr_hash', 'demo')]) }}" class="flex flex-col items-center {{ request()->routeIs('customer.catalog') ? 'text-toreno-brown font-bold' : 'text-gray-500 hover:text-toreno-accent' }} transition-colors">
        <svg class="w-6 h-6 mb-1 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
        <span class="text-[10px] uppercase tracking-wider">Katalog</span>
    </a>
    <a wire:navigate href="{{ route('customer.cart') }}" class="flex flex-col items-center {{ request()->routeIs('customer.cart') ? 'text-toreno-brown font-bold' : 'text-gray-500 hover:text-toreno-accent' }} transition-colors relative">
        <svg class="w-6 h-6 mb-1 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        <span class="text-[10px] uppercase tracking-wider">Keranjang</span>
        
        <template x-if="$store.cart.totalCount > 0">
            <span class="absolute -top-1 -right-3 bg-red-500 text-white rounded-full h-5 w-5 flex items-center justify-center text-[10px] font-black shadow-md animate-bounce" x-text="$store.cart.totalCount"></span>
        </template>
    </a>
</div>
