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
            'address_1' => 'Tokyo, Japan',
            'address_2' => 'Osaka, Japan',
            'logo_url' => 'http://logo.png',
            'greeting_msg' => 'Greeting Msg',
            'post_code' => '1234',
            'contact_name' => 'Restaurant 123',
            'contact_email' => 'restaurant1@mail.jp',
            'company_id' => 1
        ]);
    }
}
