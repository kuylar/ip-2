<?php

namespace App\Http;

use App\Models\MoneyAccount;
use App\Models\Transaction;

class WalletUtils
{
    static function getWallet(int $acc_id, int $user_id)
    {
        $acc = MoneyAccount::find($acc_id);

        if ($acc == null || $acc->user_id != $user_id) {
            return null;
        }

        return $acc;
    }

    static function getBalance(MoneyAccount $acc)
    {
        $transactions = Transaction::where("account_id", $acc->id)->get();
        $bal = 0;

        foreach ($transactions as $transaction) {
            switch ($transaction->transaction_type) {
                case "sell":
                case "deposit":
                    $bal += $transaction->amount;
                    break;
                case "buy":
                case "withdraw":
                    $bal -= $transaction->amount;
                    break;
            }
        }
        return $bal;
    }

    static function transaction(MoneyAccount $acc, string $actionType, int $amount, int $stockId)
    {
        Transaction::create([
            "user_id" => $acc["user_id"],
            "account_id" => $acc["id"],
            "transaction_type" => $actionType,
            "amount" => $amount,
            "stock_id" => $stockId
        ]);
        return true;
    }
}
