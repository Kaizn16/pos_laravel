<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UnitOfMeasureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentTimestamp = Carbon::now();

        $uom = [
            ['uom_name' => 'pack', 'status' => 1, 'created_at' => $currentTimestamp],
            ['uom_name' => 'each', 'status' => 1, 'created_at' => $currentTimestamp],
        ];

        DB::table('uom')->insert($uom);
    }
}
