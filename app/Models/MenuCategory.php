<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MenuCategory extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'sort_order'];

    public function menus()
    {
        return $this->hasMany(Menu::class, 'category_id');
    }
}
