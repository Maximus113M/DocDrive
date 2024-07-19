<?php

namespace Database\Seeders;

use App\Models\Validity;
use Illuminate\Database\Seeder;

class ValiditySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Validity::create([
            'year' => 2020
        ]);
        Validity::create([
            'year' => 2021
        ]);
        Validity::create([
            'year' => 2022
        ]);
        Validity::create([
            'year' => 2023
        ]);
        Validity::create([
            'year' => 2024
        ]);
    }
}
