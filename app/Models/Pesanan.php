<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $fillable = [
        'meja_id',
        'customer_id',
        'kode_pesanan',
        'waktu_pesan',
        'payment_status',
        'status',
        'catatan',
        'total_harga',   
    ];

    public function meja()
    {
        return $this->belongsTo(Meja::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
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
