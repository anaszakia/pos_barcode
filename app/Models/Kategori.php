<?php

namespace App\Models;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama',
        'deskripsi'
    ];

    // app/Models/Kategori.php

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

}
