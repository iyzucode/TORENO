<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Settings extends Component
{
    public $tax_rate = 0;
    public $service_charge_type = 'percentage';
    public $service_charge_rate = 0;

    public function mount()
    {
        $this->tax_rate = \App\Models\Setting::getVal('tax_rate', 0);
        $this->service_charge_type = \App\Models\Setting::getVal('service_charge_type', 'percentage');
        $this->service_charge_rate = \App\Models\Setting::getVal('service_charge_rate', 0);
    }

    public function save()
    {
        $this->validate([
            'tax_rate' => 'required|numeric|min:0|max:100',
            'service_charge_type' => 'required|in:percentage,fixed',
            'service_charge_rate' => 'required|numeric|min:0',
        ]);

        \App\Models\Setting::updateOrCreate(['key' => 'tax_rate'], ['value' => $this->tax_rate]);
        \App\Models\Setting::updateOrCreate(['key' => 'service_charge_type'], ['value' => $this->service_charge_type]);
        \App\Models\Setting::updateOrCreate(['key' => 'service_charge_rate'], ['value' => $this->service_charge_rate]);

        session()->flash('message', 'Pengaturan berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.admin.settings')->layout('layouts.app');
    }
}
