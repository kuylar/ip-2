<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoneyAccount extends Model
{
    protected $table = 'user_accounts';

    protected $fillable = [
        "user_id",
        "currency_id",
    ];
    //
}
