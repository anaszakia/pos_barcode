<?php

namespace App\Models;

use App\Models\Menu;
use App\Models\ReservationItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_reservasi',
        'nama',
        'no_hp',
        'jumlah_orang',
        'tanggal',
        'jam',
        'status_pembayaran',
        'metode_dp',
        'jumlah_bayar',
        'catatan',
        'status_reservasi'
    ];

    // public function items()
    // {
    //     return $this->belongsToMany(Menu::class, 'reservation_items')
    //                 ->withPivot('qty')
    //                 ->withTimestamps();
    // }

    public function items()
    {
        return $this->hasMany(ReservationItem::class);
    }
}
