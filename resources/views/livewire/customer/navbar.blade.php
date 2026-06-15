<div 
    x-data="{
        isScrolling: false,
        scrollTimer: null,
        badgeAnim: '',
        prevCount: 0,
        init() {
            this.prevCount = this.$store.cart.totalCount;
            window.addEventListener('scroll', () => {
                this.isScrolling = true;
                clearTimeout(this.scrollTimer);
                this.scrollTimer = setTimeout(() => {
                    this.isScrolling = false;
                }, 300);
            }, { passive: true });
            this.$watch('$store.cart.totalCount', (newVal) => {
                if (newVal > this.prevCount) {
                    this.badgeAnim = 'badge-pop';
                } else if (newVal < this.prevCount) {
                    this.badgeAnim = 'badge-shrink';
                }
                this.prevCount = newVal;
                setTimeout(() => { this.badgeAnim = ''; }, 350);
            });
        }
    }"
    :class="isScrolling 
        ? 'py-2 rounded-2xl bg-white/20 shadow-[0_4px_20px_rgba(0,0,0,0.06)] max-w-[calc(28rem-2.5rem)]' 
        : 'py-3 rounded-3xl bg-white/30 shadow-[0_8px_30px_rgba(0,0,0,0.1)] max-w-[calc(28rem-2.5rem)]'"
    class="fixed bottom-5 w-[calc(100%-2.5rem)] left-1/2 transform -translate-x-1/2 backdrop-blur-lg border border-white/40 flex justify-around items-center z-50 transition-all duration-300 ease-in-out"
>
    <a wire:navigate href="{{ route('customer.catalog', ['qr_hash' => session('qr_hash', 'demo')]) }}" :class="isScrolling ? 'flex-row gap-1' : 'flex-col'" class="flex items-center {{ request()->routeIs('customer.catalog') ? 'text-toreno-brown font-bold' : 'text-gray-500 hover:text-toreno-accent' }} transition-all duration-300">
        <svg :class="isScrolling ? 'w-4 h-4' : 'w-6 h-6 mb-1'" class="drop-shadow-sm transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
        <span class="text-[10px] uppercase tracking-wider whitespace-nowrap">Katalog</span>
    </a>
    <a wire:navigate href="{{ route('customer.cart') }}" :class="isScrolling ? 'flex-row gap-1' : 'flex-col'" class="flex items-center {{ request()->routeIs('customer.cart') ? 'text-toreno-brown font-bold' : 'text-gray-500 hover:text-toreno-accent' }} transition-all duration-300 relative">
        <svg :class="isScrolling ? 'w-4 h-4' : 'w-6 h-6 mb-1'" class="drop-shadow-sm transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        <span class="text-[10px] uppercase tracking-wider whitespace-nowrap">Keranjang</span>
        
        <template x-if="$store.cart.totalCount > 0">
            <span 
                :class="[
                    isScrolling ? 'relative ml-1' : 'absolute -top-1 -right-3',
                    badgeAnim
                ]"
                class="bg-red-500 text-white rounded-full h-5 w-5 flex items-center justify-center text-[10px] font-black shadow-md transition-all duration-300" 
                x-text="$store.cart.totalCount"
            ></span>
        </template>
    </a>

    <style>
        .badge-pop {
            animation: badgePop 350ms ease-out;
        }
        .badge-shrink {
            animation: badgeShrink 350ms ease-out;
        }
        @keyframes badgePop {
            0%   { transform: scale(1); }
            40%  { transform: scale(1.5); }
            100% { transform: scale(1); }
        }
        @keyframes badgeShrink {
            0%   { transform: scale(1); }
            40%  { transform: scale(0.5); }
            100% { transform: scale(1); }
        }
    </style>
</div>
