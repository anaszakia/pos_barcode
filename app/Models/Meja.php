<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meja extends Model
{
    use HasFactory;
    protected $fillable = [
        'nomor_meja',
        'lantai',
        'lokasi'
    ];
}
