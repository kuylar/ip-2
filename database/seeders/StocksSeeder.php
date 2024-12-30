<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\CurrencyValue;
use App\Models\Stock;
use App\Models\StockValue;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use http\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class StocksSeeder extends Seeder
{
    const API_KEY = "7PF3WO3VPS1LWKB5";
    const STOCKS = [
        "AAPL", // apple
        "AMZN", // amazon
        "NVDA", // nvidia
        "AMD",  // amd
        "INTC", // intel
        "META", // meta/facebook

    ];

    public function run(): void
    {
        foreach (self::STOCKS as $code) {
            $pricesRes = Http::get("https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=" . $code . "&outputsize=compact&apikey=" . self::API_KEY);
            $pricesJson = $pricesRes->json();
            $overviewRes = Http::get("https://www.alphavantage.co/query?function=OVERVIEW&symbol=" . $code . "&apikey=" . self::API_KEY);
            $overviewJson = $overviewRes->json();
            $s = Stock::create([
                "code" => $code,
                "name" => $overviewJson["Name"],
                "description" => $overviewJson["Description"],
            ]);
            print("[$code] $s->name veritabanına eklendi\n");
            foreach ($pricesJson["Time Series (Daily)"] as $date => $value) {
                StockValue::create([
                    "stock_id" => $s->id,
                    "value" => $value["1. open"],
                    "created_at" => $date,
                ]);
            }
        }
    }
}
