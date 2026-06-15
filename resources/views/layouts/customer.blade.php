<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Coffee TORENO') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            /* Mencegah double tap zoom dan pinch zoom di iOS */
            html, body {
                touch-action: pan-x pan-y;
            }
            /* Mencegah auto-zoom saat fokus ke input di iOS (iOS zoom jika font < 16px) */
            input, select, textarea {
                font-size: 16px !important;
            }
        </style>
        <script>
            // Mencegah gesture pinch-to-zoom di iOS Safari
            document.addEventListener('gesturestart', function (e) {
                e.preventDefault();
            });
        </script>
        @livewireStyles
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-100">
        <div class="min-h-screen flex flex-col items-center">
            
            <!-- Mobile Container (Simulates phone on desktop) -->
            <div class="w-full max-w-md bg-toreno-light min-h-screen shadow-2xl relative pb-24">
                
                <!-- Header -->
                <div class="w-full bg-toreno-brown shadow-md py-4 px-6 sticky top-0 z-40 flex justify-between items-center rounded-b-2xl">
                    <div class="flex items-center">
                        <img src="https://res.cloudinary.com/dtbut0lkj/image/upload/v1781551235/logotoreno-nobg_eeipti.png" alt="Logo TORENO" class="h-8 object-contain">
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('customer.history') }}" class="text-white hover:text-toreno-cream transition" aria-label="Riwayat Pesanan" title="Riwayat Pesanan">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </a>
                        @if(session()->has('table_number'))
                            <span class="bg-toreno-cream text-toreno-brown text-xs font-extrabold px-4 py-1.5 rounded-full shadow-sm">
                                Meja {{ session('table_number') }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Content -->
                <div class="w-full px-5 py-6">
                    {{ $slot }}
                </div>

                <!-- Bottom Navigation for Customer (Liquid Glass Floating Pill) -->
                @if(!isset($hideNavbar) || !$hideNavbar)
                    <livewire:customer.navbar />
                @endif
                
            </div>
        </div>
        @livewireScripts
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('cart', {
                    items: JSON.parse(localStorage.getItem('toreno_cart') || '{}'),
                    
                    save() {
                        localStorage.setItem('toreno_cart', JSON.stringify(this.items));
                    },

                    add(menu) {
                        if (!this.items[menu.id]) {
                            this.items[menu.id] = { 
                                id: menu.id, 
                                name: menu.name, 
                                price: menu.price, 
                                quantity: 1, 
                                notes: '' 
                            };
                        } else {
                            this.items[menu.id].quantity++;
                        }
                        this.save();
                    },

                    decrease(id) {
                        if (this.items[id]) {
                            if (this.items[id].quantity > 1) {
                                this.items[id].quantity--;
                            } else {
                                delete this.items[id];
                            }
                            this.save();
                        }
                    },

                    remove(id) {
                        if (this.items[id]) {
                            delete this.items[id];
                            this.save();
                        }
                    },

                    increase(id) {
                        if (this.items[id]) {
                            this.items[id].quantity++;
                            this.save();
                        }
                    },

                    updateNotes(id, notes) {
                        if (this.items[id]) {
                            this.items[id].notes = notes;
                            this.save();
                        }
                    },

                    get totalCount() {
                        return Object.values(this.items).reduce((count, item) => count + item.quantity, 0);
                    },

                    discountAmount: 0,
                    appliedPromoCode: null,

                    setPromo(code, amount) {
                        this.appliedPromoCode = code;
                        this.discountAmount = amount;
                    },

                    clearPromo() {
                        this.appliedPromoCode = null;
                        this.discountAmount = 0;
                    },

                    taxRate: {{ (float)\App\Models\Setting::getVal('tax_rate', 0) }},
                    serviceChargeType: '{{ \App\Models\Setting::getVal('service_charge_type', 'percentage') }}',
                    serviceChargeRate: {{ (float)\App\Models\Setting::getVal('service_charge_rate', 0) }},
                    
                    get subtotalAmount() {
                        return Object.values(this.items).reduce((total, item) => total + (item.price * item.quantity), 0);
                    },

                    get netSubtotal() {
                        let net = this.subtotalAmount - this.discountAmount;
                        return net > 0 ? net : 0;
                    },

                    get taxAmount() {
                        return this.netSubtotal * (this.taxRate / 100);
                    },

                    get serviceChargeAmount() {
                        if (this.serviceChargeType === 'fixed') {
                            return this.serviceChargeRate;
                        }
                        return this.netSubtotal * (this.serviceChargeRate / 100);
                    },

                    get totalAmount() {
                        return this.netSubtotal + this.taxAmount + this.serviceChargeAmount;
                    },
                    
                    clear() {
                        this.items = {};
                        this.save();
                    }
                });
            });
        </script>
    </body>
</html>
