<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    protected $fillable = [
        'pesanan_id',
        'menu_id',
        'varian',
        'note',
        'jumlah',
        'harga',
        'subtotal',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menus::class);
    }
    public function pembayaran()
{
    return $this->hasOne(Pembayaran::class, 'pesanan_id');
}
}
