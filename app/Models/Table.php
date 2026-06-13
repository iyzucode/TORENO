<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Table extends Model
{
    use HasUuids;

    protected $fillable = [
        'table_number', 'qr_code_hash'
    ];
}
