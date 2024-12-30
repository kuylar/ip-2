<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserStock extends Model
{
    protected $fillable = [
        "user_id",
        "stock_id",
        "transaction_type",
        "stock_amount",
        "base_money_amount"
    ];
}
