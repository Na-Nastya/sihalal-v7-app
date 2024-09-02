<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'access_token',
        'refresh_token',
    ];

    // Anda bisa menambahkan relasi dengan model User jika diperlukan
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

}
