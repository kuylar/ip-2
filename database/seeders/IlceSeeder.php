<?php

namespace Database\Seeders;

use App\Models\Ilce;
use App\Models\Sehir;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class IlceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $ilce_arr = Storage::disk("private")->json("ilce.json")[2]["data"];
        foreach ($ilce_arr as $ilce) {
            print($ilce["ilce_title"] . "\n");
            Ilce::create([
                "name" => $ilce["ilce_title"],
                "key" => $ilce["ilce_key"],
                "sehir_key" => $ilce["ilce_sehirkey"],
            ]);
        }
    }
}
