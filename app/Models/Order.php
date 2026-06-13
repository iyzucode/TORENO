<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Order extends Model
{
    use HasUuids;

    protected $fillable = [
        'order_code', 'table_id', 'customer_name', 'customer_phone', 'status', 'payment_status', 'payment_method', 'snap_token', 'total_amount', 'notes'
    ];

    public function table() {
        return $this->belongsTo(Table::class);
    }

    public function items() {
        return $this->hasMany(OrderItem::class);
    }
}
