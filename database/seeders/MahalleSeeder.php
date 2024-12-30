<?php

namespace Database\Seeders;

use App\Models\Mahalle;
use App\Models\Sehir;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class MahalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $mahalle_arr = Storage::disk("private")->json("mahalle.json")[2]["data"];
        foreach ($mahalle_arr as $mahalle) {
            print($mahalle["mahalle_title"] . "\n");
            Mahalle::create([
                "name" => $mahalle["mahalle_title"],
                "key" => $mahalle["mahalle_key"],
                "ilce_key" => $mahalle["mahalle_ilcekey"],
            ]);
        }
    }
}
