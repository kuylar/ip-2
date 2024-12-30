<?php

namespace App\Http\Controllers;

use App\Http\WalletUtils;
use App\Models\Currency;
use App\Models\CurrencyValue;
use App\Models\MoneyAccount;
use App\Models\NewsPost;
use App\Models\Stock;
use App\Models\StockValue;
use App\Models\UserStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StocksController extends Controller
{
    function getStockInfo(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/auth/login');
        }

        $model = [
            "user" => Auth::user(),
            "stocks" => Stock::all(),
        ];
        $stockId = $request->get("s");
        $stockCode = $request->get("code");
        $stock = $stockId != null
            ? Stock::find($stockId) :
            ($stockCode != null
                ? Stock::where("code", "LIKE", "%$stockCode%")->first()
                : null);
        if ($stock != null) {
            $stockId = $stock->id;
            $model["stockId"] = $stock->id;
            $model["stock"] = $stock;
            $prices = StockValue::select('value', 'created_at')->orderBy("created_at", "DESC")->where("stock_id", "=", $stockId)->limit(14)->get();
            $usdPrice = $prices[0]["value"];

            $model["prices"] = [];
            $model["chart"] = [
                "labels" => [],
                "values" => []
            ];
            foreach ($prices as $price) {
                array_push($model["chart"]["labels"], $price["created_at"]->toDateString());
                array_push($model["chart"]["values"], $price["value"]);
            }

            $model["curPrice"] = [];
            foreach (Currency::all() as $currency) {
                $cValue = CurrencyValue::orderBy("created_at", "DESC")->where("currency_id", "=", $currency->id)->get()[0]["value"];
                $model["curPrice"][$currency->code] = $cValue * $usdPrice;
            };

            $model["acc"] = MoneyAccount::where("user_id", Auth::id())->get()[0];
            $model["userBalance"] = WalletUtils::getBalance($model["acc"]);
            $model["balanceCurrencyText"] = strtoupper(Currency::find($model["acc"]->currency_id)["code"]);

            $userStocks = UserStock::where("user_id", Auth::id())->where("stock_id", $stockId)->get();
            $userStockAmount = 0;

            foreach ($userStocks as $userStock) {
                if ($userStock->transaction_type == "buy")
                    $userStockAmount += $userStock->stock_amount;
                else
                    $userStockAmount -= $userStock->stock_amount;
            }

            $model["userStockAmount"] = $userStockAmount;
        } else {
            $userStocks = UserStock::where("user_id", Auth::id())->get();
            $userPortfolio = [];
            foreach ($userStocks as $userStock) {
                if (!isset($userPortfolio[$userStock->stock_id])) {
                    $userPortfolio[$userStock->stock_id] = [
                        "amnt" => 0,
                        "id" => $userStock->stock_id
                    ];
                }
                if ($userStock->transaction_type == "buy")
                    $userPortfolio[$userStock->stock_id]["amnt"] += $userStock->stock_amount;
                else
                    $userPortfolio[$userStock->stock_id]["amnt"] -= $userStock->stock_amount;
            }
            $model["portfolyo"] = [];
            foreach ($userPortfolio as $stockId => $portfolioItem) {
                $portfolioStock = Stock::find($stockId);
                $userPortfolio[$stockId]["code"] = $portfolioStock["code"];
                $userPortfolio[$stockId]["name"] = $portfolioStock["name"];

                $stockValue = StockValue::where("stock_id", "=", $stockId)->orderBy("created_at", "DESC")->first()["value"];
                $userPortfolio[$stockId]["value"] = $stockValue * $portfolioItem["amnt"];
                $userPortfolio[$stockId]["singleValue"] = $stockValue;

                if ($portfolioItem["amnt"] > 0)
                    $model["portfolyo"][$stockId] = $userPortfolio[$stockId];
            }
            $model["chart"] = [
                "labels" => [],
                "values" => []
            ];
            foreach ($model["portfolyo"] as $pItem) {
                array_push($model["chart"]["labels"], $pItem["code"]);
                array_push($model["chart"]["values"], $pItem["value"]);
            }
        }
        return view('stocks.info', $model);
    }

    function getStockNews(Request $request)
    {
        $stockId = $_GET["id"];
        return response()->json(NewsPost::where("stock_id", "=", $stockId)->paginate(5));
    }

    function buyStock(Request $request, int $id)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                "message" => "Kullanıcı giriş yapmadı"
            ], 401);
        }

        $validated = $request->validate([
            "amount" => "required|numeric",
            "account" => "required|numeric",
        ]);


        $acc = WalletUtils::getWallet($validated["account"], Auth::id());
        $currencyValue = CurrencyValue::orderBy("created_at", "DESC")->where("currency_id", "=", $acc["currency_id"])->get()[0]["value"];
        $balance = WalletUtils::getBalance($acc);

        $baseBalance = $balance / $currencyValue;

        $prices = StockValue::select('value', 'created_at')->orderBy("created_at", "DESC")->where("stock_id", "=", $id)->limit(1)->get();
        $usdPrice = $prices[0]["value"];

        $total = $usdPrice * $validated["amount"];

        if ($balance < $total) {
            return response()->json([
                "success" => false,
                "message" => "Yetersiz bakiye. İşleminizin toplamı $total, fakat elinizde $balance USD var"
            ]);
        }

        $success = WalletUtils::transaction($acc, "buy", $total * $currencyValue, $id);

        if (!$success) {
            return response()->json([
                "success" => false,
                "message" => "Bilinmeyen hata"
            ]);
        }

        UserStock::create([
            "user_id" => Auth::id(),
            "stock_id" => $id,
            "transaction_type" => "buy",
            "stock_amount" => $validated["amount"],
            "base_money_amount" => $total * $currencyValue
        ]);

        return response()->json([
            "success" => true,
            "message" => ""
        ]);
    }

    function sellStock(Request $request, int $id)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                "message" => "Kullanıcı giriş yapmadı"
            ], 401);
        }

        $validated = $request->validate([
            "amount" => "required|numeric",
            "account" => "required|numeric",
        ]);

        $acc = WalletUtils::getWallet($validated["account"], Auth::id());
        $currencyValue = CurrencyValue::orderBy("created_at", "DESC")->where("currency_id", "=", $acc["currency_id"])->get()[0]["value"];

        $prices = StockValue::select('value', 'created_at')->orderBy("created_at", "DESC")->where("stock_id", "=", $id)->limit(1)->get();
        $usdPrice = $prices[0]["value"];

        $total = $usdPrice * $validated["amount"];

        $success = WalletUtils::transaction($acc, "sell", $total * $currencyValue, $id);

        if (!$success) {
            return response()->json([
                "success" => false,
                "message" => "Bilinmeyen hata"
            ]);
        }

        UserStock::create([
            "user_id" => Auth::id(),
            "stock_id" => $id,
            "transaction_type" => "sell",
            "stock_amount" => $validated["amount"],
            "base_money_amount" => $total * $currencyValue
        ]);

        return response()->json([
            "success" => true,
            "message" => ""
        ]);
    }
}
