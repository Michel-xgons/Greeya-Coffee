<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pesanan extends Model
{
    protected $table = 'pesanan';

    protected $fillable = [
        'id_pesanan',
        'id_meja',
        'kode_pesanan',
        'total_harga',
        'status_pesanan',
        'xendit_invoice_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(DetailPesanan::class);
    }
}
