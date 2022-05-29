<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VehiclesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vehicles')->insert([
            [
                'license_plate' => rand(100,999) .  Str::random(3),
                'make' => 'Ford',
                'type' => 1
            ],
            [
                'license_plate' => rand(100,999) .  Str::random(3),
                'make' => 'GM',
                'type' => 1
            ],
            [
                'license_plate' => rand(100,999) .  Str::random(3),
                'make' => 'Pontiac',
                'type' => 1
            ],
            [
                'license_plate' => rand(100,999) .  Str::random(3),
                'make' => 'Chrysler',
                'type' => 2
            ],
            [
                'license_plate' => rand(100,999) .  Str::random(3),
                'make' => 'Nissan',
                'type' => 2
            ],
            [
                'license_plate' => rand(100,999) .  Str::random(3),
                'make' => 'VW',
                'type' => 2
            ]             
            ]);
    }
}
