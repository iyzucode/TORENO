<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Coffee TORENO') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-100">
        <div class="min-h-screen flex flex-col items-center">
            
            <!-- Mobile Container (Simulates phone on desktop) -->
            <div class="w-full max-w-md bg-toreno-light min-h-screen shadow-2xl relative pb-24">
                
                <!-- Header -->
                <div class="w-full bg-toreno-brown shadow-md py-4 px-6 sticky top-0 z-40 flex justify-between items-center rounded-b-2xl">
                    <div class="text-white font-black text-xl tracking-wider flex items-center">
                        <svg class="w-6 h-6 mr-2 text-toreno-accent" fill="currentColor" viewBox="0 0 24 24"><path d="M2,21V19H20V21H2M20,8V5H18V8H20M20,3A2,2 0 0,1 22,5V8A2,2 0 0,1 20,10H18V13A4,4 0 0,1 14,17H8A4,4 0 0,1 4,13V3H20M16,5H6V13A2,2 0 0,0 8,15H14A2,2 0 0,0 16,13V5Z" /></svg>
                        TORENO
                    </div>
                    <div>
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
                <livewire:customer.navbar />
                
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
                        return Object.keys(this.items).length;
                    },

                    get totalAmount() {
                        return Object.values(this.items).reduce((total, item) => total + (item.price * item.quantity), 0);
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
