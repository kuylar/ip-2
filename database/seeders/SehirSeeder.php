<?php

namespace Database\Seeders;

use App\Models\Sehir;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SehirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $sehir_arr = Storage::disk("private")->json("sehir.json")[2]["data"];
        foreach ($sehir_arr as $sehir) {
            print($sehir["sehir_title"] . "\n");
            Sehir::create([
                "name" => $sehir["sehir_title"],
                "key" => $sehir["sehir_key"],
            ]);
        }
    }
}
