<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pesanan;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'no_telpon',
    ];

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class);
    }
}
