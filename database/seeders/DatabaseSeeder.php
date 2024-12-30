<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $cs = new CurrencySeeder();
        $cs->run();
        $st = new StocksSeeder();
        $st->run();
        $sns = new StocksNewsSeeder();
        $sns->run();
        $ss = new SehirSeeder();
        $ss->run();
        $is = new IlceSeeder();
        $is->run();
        $ms = new MahalleSeeder();
        $ms->run();
    }
}
