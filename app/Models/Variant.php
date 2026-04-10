<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $fillable = [
        'menu_id',
        'nama_variant',
        'harga'
    ];

    public function menu()
    {
        return $this->belongsTo(Menus::class, 'menu_id');
    }
}
