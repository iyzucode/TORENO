<?php

namespace App\Livewire\Customer;

use App\Models\Menu;
use Livewire\Component;

class MenuCatalog extends Component
{
    public function mount($qr_hash = null)
    {
        if ($qr_hash) {
            $table = \App\Models\Table::where('qr_code_hash', $qr_hash)->first();
            if ($table) {
                session([
                    'table_id' => $table->id, 
                    'table_number' => $table->table_number,
                    'qr_hash' => $qr_hash
                ]);
            } else {
                abort(404, 'Meja tidak valid atau tidak ditemukan.');
            }
        }
    }

    public function render()
    {
        $menusByCategory = Menu::where('is_available', true)
            ->with('category')
            ->orderBy('sort_order')
            ->get()
            ->sortBy(fn($menu) => $menu->category->sort_order ?? 999)
            ->groupBy(fn($menu) => $menu->category->name ?? 'Lainnya');

        return view('livewire.customer.menu-catalog', [
            'menusByCategory' => $menusByCategory,
        ])->layout('layouts.customer');
    }
}
