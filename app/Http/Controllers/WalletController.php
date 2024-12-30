<?php

namespace App\Http\Controllers;

use App\Http\WalletUtils;
use App\Models\Currency;
use App\Models\MoneyAccount;
use App\Models\Stock;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class WalletController extends Controller
{
    function getWallet(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        $accountId = MoneyAccount::where("user_id", Auth::user()->id)->first();
        if ($accountId == null) {
            return redirect("/wallet/new");
        }

        $currencies = [];

        foreach (Currency::all() as $c) {
            $currencies[$c->id] = $c;
        }

        $accounts = MoneyAccount::where("user_id", Auth::user()->id)->get();
        $acc = isset($_GET["id"]) ? MoneyAccount::find($_GET["id"]) : $accounts->first();

        // "authorization"
        if ($acc->user_id != Auth::id()) {
            return redirect("/wallet?id=" . $accounts->first()->id);
        }

        $stockNames = [];
        foreach (Stock::all() as $stock) {
            $stockNames[$stock->id] = $stock->name;
        }

        $model = [
            "user" => Auth::user(),
            "thisAcc" => $acc,
            "accounts" => $accounts,
            "currencies" => $currencies,
            "thisCurrency" => $currencies[$accountId->currency_id],
            "balance" => WalletUtils::getBalance($acc),

            "stockInfos" => $stockNames
        ];

        return view('wallet.info', $model);
    }

    function newWallet()
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        $model = [
            "user" => Auth::user(),
            "currencies" => Currency::all(),
        ];

        return view('wallet.new', $model);
    }

    function createWallet(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        $validated = $request->validate([
            "currency" => "required",
        ]);

        MoneyAccount::create([
            "user_id" => Auth::id(),
            "currency_id" => $validated["currency"],
        ]);

        return redirect("/wallet");
    }

    function getTransactions(Request $request, int $id)
    {
        $account = WalletUtils::getWallet($id, Auth::id());
        if ($account == null) return response()->json([]);
        return response()->json(Transaction::where("account_id", $account->id)->orderBy("created_at", "DESC")->paginate(10));
    }

    function deposit(Request $request, int $id)
    {
        if (!Auth::check()) {
            return response()->status(401);
        }


        $acc = WalletUtils::getWallet($id, Auth::id());

        if ($acc == null) {
            http_response_code(404);
            return Response::json([
                "status" => false,
                "message" => "Cüzdan bulunamadı"
            ], 404);
        }

        $validated = $request->validate([
            "amount" => "required|numeric",
        ]);


        Transaction::create([
            "user_id" => Auth::id(),
            "account_id" => $acc->id,
            "transaction_type" => "deposit",
            "amount" => $validated["amount"],
        ]);

        return response()->json([
            "success" => true,
            "newBalance" => WalletUtils::getBalance($acc)
        ]);
    }

    function withdraw(Request $request, int $id)
    {
        if (!Auth::check()) {
            return response()->status(401);
        }

        $acc = WalletUtils::getWallet($id, Auth::id());

        if ($acc == null) {
            http_response_code(404);
            return Response::json([
                "status" => false,
                "message" => "Cüzdan bulunamadı"
            ], 404);
        }

        $validated = $request->validate([
            "amount" => "required|numeric",
        ]);

        $bal = WalletUtils::getBalance($acc);

        if ($bal < $validated["amount"]) {
            return response()->json([
                "success" => false,
                "message" => "Yetersiz bakiye"
            ], 400);
        } else {
            Transaction::create([
                "user_id" => Auth::id(),
                "account_id" => $acc->id,
                "transaction_type" => "withdraw",
                "amount" => $validated["amount"],
            ]);
        }

        return response()->json([
            "success" => true,
            "newBalance" => WalletUtils::getBalance($acc)
        ]);
    }
}
