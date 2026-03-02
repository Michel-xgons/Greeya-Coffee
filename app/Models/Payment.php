<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesanan_id',
        'external_id',
        'kode_pembayaran',
        'xendit_invoice_id',
        'invoice_url',
        'metode_pembayaran',
        'total_bayar',
        'status_pembayaran',
        'waktu_bayar',
        'callback_payload',
    ];

    protected $casts = [
        'waktu_bayar' => 'datetime',
        'callback_payload' => 'array',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}
