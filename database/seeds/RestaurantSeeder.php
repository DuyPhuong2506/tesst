<?php

use Illuminate\Database\Seeder;
use App\Models\Restaurant;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Restaurant::create([
            'id' => 1,
            'name' => 'Restaurant 1',
            'phone' => '0123456789',
            'address' => 'Tokyo, Japan',
            'logo_url' => 'http://logo.png',
            'greeting_msg' => 'Greeting Msg'
        ]);
    }
}
