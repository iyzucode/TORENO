<?php

namespace App\Livewire\Customer;

use Livewire\Component;

class Cart extends Component
{
    public function render()
    {
        return view('livewire.customer.cart')->layout('layouts.customer');
    }
}
