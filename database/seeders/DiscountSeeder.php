<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentTimestamp = Carbon::now();

        $discounts = [
            ['discount_type' => 'Suki Discount', 'discount_percentage' => 10, 'created_at' => $currentTimestamp],
            ['discount_type' => 'Senior Discount', 'discount_percentage' => 20, 'created_at' => $currentTimestamp],
        ];

        DB::table('discount')->insert($discounts);
    }
}
