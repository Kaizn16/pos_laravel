<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentTimestamp = Carbon::now();

        $payment_methods = [
            ['payment_method_type' => 'Cash', 'created_at' => $currentTimestamp],
            ['payment_method_type' => 'GCash', 'created_at' => $currentTimestamp],
        ];

        DB::table('payment_method')->insert($payment_methods);
    }
}
