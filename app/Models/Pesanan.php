<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $fillable = [
        'meja_id',
        'kode_pesanan',
        'total_harga',
        'status',
        'xendit_invoice_id',
    ];

    public function meja()
    {
        return $this->belongsTo(Meja::class);
    }

    
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class);
    }
}
