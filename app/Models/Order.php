<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Meja;
use App\Models\OrderItem;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'meja_id',
        'kode_pesanan',
        'status',
        'total_bayar',
        'jenis_pembayaran',
        'status_pembayaran'
    ];

    // Tambahkan relasi ke order_items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Jika kamu punya relasi ke meja
    public function meja()
    {
        return $this->belongsTo(Meja::class);
    }
}
