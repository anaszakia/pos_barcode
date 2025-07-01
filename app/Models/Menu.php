<?php

namespace App\Models;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;
    protected $fillable = [
        'kategori_id',
        'nama',
        'harga',
        'deskripsi',
        'image'
    ];

    // Relasi ke model Kategori (Many to One)
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
