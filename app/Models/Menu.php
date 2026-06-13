<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Menu extends Model
{
    use HasUuids;

    protected $fillable = [
        'name', 'description', 'price', 'image_url', 'category', 'is_available'
    ];
}
