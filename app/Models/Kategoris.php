<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategoris extends Model
{
    use HasFactory;

    protected $fillable = ['id_kategori'];

    public function menus()
    {
        return $this->hasMany(Menus::class, 'kategori_id');
    }
}
