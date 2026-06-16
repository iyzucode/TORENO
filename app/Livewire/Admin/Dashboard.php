<?php

namespace App\Livewire\Admin;

use App\Models\Menu;
use App\Models\User;
use App\Models\Table;
use App\Models\PromoCode;
use App\Models\Promotion;
use App\Models\PopupPromotion;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $totalActiveMenus = Menu::where('is_available', true)->count();
        $outOfStockMenus = Menu::where('is_available', false)->get();
        $totalTables = Table::count();
        $totalUsers = User::count();

        $activePromoCodes = PromoCode::where('is_active', true)->get();
        $activeSliders = Promotion::where('is_active', true)->get();
        $activePopup = PopupPromotion::where('is_active', true)->first();

        $totalPromotions = $activePromoCodes->count() + $activeSliders->count() + ($activePopup ? 1 : 0);

        return view('livewire.admin.dashboard', [
            'totalActiveMenus' => $totalActiveMenus,
            'outOfStockMenus' => $outOfStockMenus,
            'totalTables' => $totalTables,
            'totalUsers' => $totalUsers,
            'activePromoCodes' => $activePromoCodes,
            'activeSliders' => $activeSliders,
            'activePopup' => $activePopup,
            'totalPromotions' => $totalPromotions,
        ])->layout('layouts.app');
    }
}
