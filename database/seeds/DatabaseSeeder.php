<?php

use Illuminate\Database\Seeder;
// use Database\Seeders\CompanySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(CompanyTableSeeder::class);
        // $this->call(CustomerSeeder::class);
        // $this->call(TableAccountSeeder::class);
        // $this->call(RestaurantSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CustomerTaskSeeder::class);
    }
}
