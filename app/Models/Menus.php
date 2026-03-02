<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    protected $table    = 'menus';

    protected $fillable = [
        'id_kategori',
        'nama_menu',
        'harga',
        'deskripsi',
        'gambar',
        'status',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategoris::class, 'kategori_id');
    }
}
