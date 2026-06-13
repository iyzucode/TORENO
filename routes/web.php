<?php

use Illuminate\Support\Facades\Route;

// Customer Routes
Route::get('/t/{qr_hash}', \App\Livewire\Customer\MenuCatalog::class)->name('customer.catalog');
Route::get('/cart', \App\Livewire\Customer\Cart::class)->name('customer.cart');
Route::get('/checkout', \App\Livewire\Customer\Checkout::class)->name('customer.checkout');
Route::get('/history', \App\Livewire\Customer\OrderHistory::class)->name('customer.history');
Route::get('/order/{order_id}', \App\Livewire\Customer\OrderTracking::class)->name('customer.order');

Route::post('/midtrans/callback', [\App\Http\Controllers\MidtransController::class, 'callback'])->name('midtrans.callback');

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        $role = auth()->user()->role;
        if ($role === 'owner') return redirect()->route('owner.dashboard');
        if ($role === 'admin') return redirect()->route('admin.dashboard');
        if ($role === 'cashier') return redirect()->route('cashier.dashboard');
        if ($role === 'kitchen') return redirect()->route('kitchen.dashboard');
        return view('dashboard');
    })->name('dashboard');

    Route::view('profile', 'profile')->name('profile');

    // Owner Routes
    Route::middleware(['role:owner,admin'])->prefix('owner')->name('owner.')->group(function () {
        Route::get('/dashboard', \App\Livewire\Owner\Dashboard::class)->name('dashboard');
    });

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
        Route::get('/menus', \App\Livewire\Admin\MenuManagement::class)->name('menus');
        Route::get('/tables', \App\Livewire\Admin\TableManagement::class)->name('tables');
    });

    // Cashier Routes
    Route::middleware(['role:cashier,admin'])->prefix('cashier')->name('cashier.')->group(function () {
        Route::get('/dashboard', \App\Livewire\Cashier\Dashboard::class)->name('dashboard');
    });

    // Kitchen Routes
    Route::middleware(['role:kitchen,admin'])->prefix('kitchen')->name('kitchen.')->group(function () {
        Route::get('/dashboard', \App\Livewire\Kitchen\Dashboard::class)->name('dashboard');
    });
});

require __DIR__.'/auth.php';
