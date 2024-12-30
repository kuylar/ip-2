<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\CurrencyValue;
use App\Models\NewsCategory;
use App\Models\NewsPost;
use App\Models\Stock;
use App\Models\StockValue;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use http\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class StocksNewsSeeder extends Seeder
{
    public function run(): void
    {
        $categories = NewsCategory::all();
        if ($categories->count() === 0) {
            NewsCategory::create([
                "name" => "Varsayılan Kategori"
            ]);
            $categories = NewsCategory::all();
        }
        $catId = $categories->first()->id;
        foreach (StocksSeeder::STOCKS as $code) {
            $res = Http::get("https://www.alphavantage.co/query?function=NEWS_SENTIMENT&tickers=" . $code . "&apikey=" . StocksSeeder::API_KEY);
            $json = $res->json();
            print("[$code] için haberler veritabanına eklendi\n");
            foreach ($json["feed"] as $item) {
                NewsPost::create([
                    "stock_id" => Stock::where("code", "=", $code)->first()->id,
                    "title" => $item["title"],
                    "summary" => $item["summary"],
                    "image" => $item["banner_image"],
                    "source" => $item["url"],
                    "category_id" => $catId
                ]);
            }
        }
    }
}
