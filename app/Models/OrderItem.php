<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class OrderItem extends Model
{
    use HasUuids;

    protected $fillable = [
        'order_id', 'menu_id', 'quantity', 'notes'
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function menu() {
        return $this->belongsTo(Menu::class);
    }
}
