<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        "user_id",
        "account_id",
        "transaction_type",
        "amount",
        "stock_id"
    ];
}
