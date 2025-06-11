<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_buku';

    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'cover',
        'genre',
        'status',
        'harga',
    ];
}
