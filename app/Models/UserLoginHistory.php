<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLoginHistory extends Model
{
    protected $fillable = [
        "user_id",
        "params",
        "login_type",
    ];
}
