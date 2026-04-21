<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TerlecSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('terlec')->insertOrIgnore([
            ['id' =>  3, 'ano' => 2021, 'orden' =>  6],
            ['id' =>  4, 'ano' => 2022, 'orden' =>  5],
            ['id' =>  5, 'ano' => 2016, 'orden' => 11],
            ['id' =>  6, 'ano' => 2017, 'orden' => 10],
            ['id' =>  7, 'ano' => 2018, 'orden' =>  9],
            ['id' =>  8, 'ano' => 2019, 'orden' =>  8],
            ['id' =>  9, 'ano' => 2020, 'orden' =>  7],
            ['id' => 10, 'ano' => 2023, 'orden' =>  4],
            ['id' => 11, 'ano' => 2024, 'orden' =>  3],
            ['id' => 12, 'ano' => 2025, 'orden' =>  2],
            ['id' => 13, 'ano' => 2026, 'orden' =>  1],
        ]);
    }
}
