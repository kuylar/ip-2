<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        "user_id",
        "sehir_id",
        "ilce_id",
        "mahalle_id",
        "address"
    ];
}
