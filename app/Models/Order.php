<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Order extends Model
{
    use HasUuids;

    protected $fillable = [
        'table_id', 'status', 'payment_method', 'total_amount', 'notes'
    ];

    public function table() {
        return $this->belongsTo(Table::class);
    }

    public function items() {
        return $this->hasMany(OrderItem::class);
    }
}
