<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Parking_AccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('parking_access')->insert([
            [
                'license_plate' => '123ABC',
                'access_time' => '2022-05-26 08:03:21',
                'leave_time' => '2022-05-26 19:32:54'
            ],
            [
                'license_plate' => 'LMN2345',
                'access_time' => '2022-05-25 19:23:09',
                'leave_time' => '2022-05-26 08:12:43'
            ],
            [
                'license_plate' => 'SDF765',
                'access_time' => '2022-05-26 12:45:26',
                'leave_time' => '2022-05-26 13:32:04'
            ]
        ]);
    }
}
