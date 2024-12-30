<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\CurrencyValue;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use http\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            "try" => "Türk Lirası",
            "eur" => "Euro",
            "usd" => "Amerikan Doları",
            "gbp" => "İngiliz Sterlini",
            "jpy" => "Japon Yeni",
            "sek" => "İsveç Kronu",
            "cad" => "Kanada Doları",
            "hdk" => "Hong Kong Doları",
            "azn" => "Azerbaycan Manatı",
            "cny" => "Çin Yeni",
        ];
        $base = "usd";
        $res = Http::get("https://cdn.jsdelivr.net/npm/@fawazahmed0/currency-api@latest/v1/currencies/$base.json");
        $json = $res->json();
        foreach ($json[$base] as $currency => $value) {
            if (!array_key_exists($currency, $names)) continue;
            print("[$currency] $names[$currency] veritabanına eklendi\n");
            $c = Currency::create([
                "code" => $currency,
                "name" => $names[$currency],
            ]);
            CurrencyValue::create([
               "currency_id" => $c->id,
               "value" => $value
            ]);
        }
    }
}
