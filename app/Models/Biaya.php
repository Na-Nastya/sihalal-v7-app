<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biaya extends Model
{
    use HasFactory;

    // Tentukan kolom yang dapat diisi (mass assignable) sesuai dengan struktur tabel di database
    protected $fillable = [
        'id_reg',
        'keterangan',
        'qty',
        'harga',
    ];
}
