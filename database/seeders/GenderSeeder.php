<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentTimestamp = Carbon::now();

        $genders = [
            ['gender_type' => 'Male', 'created_at' => $currentTimestamp],
            ['gender_type' => 'Female', 'created_at' => $currentTimestamp],
        ];

        DB::table('gender')->insert($genders);
    }
}
