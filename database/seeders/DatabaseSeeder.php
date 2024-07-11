<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /* \App\Models\Product::factory(10)->create(); */
        // Seed individual tables
        $this->call(GenderSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(DiscountSeeder::class);

        // Seed Users table
        $this->seedUsers();
    }

    /**
     * Seed the users table.
     *
     * @return void
     */
    private function seedUsers()
    {
        // Admin User
        /* DB::table('users')->insert([
             'role_id' => 1,
             'gender_id' => rand(1, 3),
             'name' => 'Mark Romel Feguro',
             'email' => 'markromelfeguro1@gmail.com',
             'username' => 'admin',
             'password' => Hash::make('123'),
             'contact_number' => '09672812221',
             'user_image' => 'default-user.png',
        ]); */
    }
}