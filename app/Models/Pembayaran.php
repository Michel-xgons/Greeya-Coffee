<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesanan_id',
        'xendit_external_id',
        'payment_type',
        'transaction_status',
        'gross_amount',
        'invoice_url',
        'expiry_time',
        'transaction_time',

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
