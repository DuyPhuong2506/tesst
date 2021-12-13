<?php

use Illuminate\Database\Seeder;
use App\Models\Place;
use App\Models\Wedding;
use App\Models\TablePosition;
use App\Models\Customer;
use App\Models\Restaurant;

class WeddingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $restaurant = Restaurant::create([
            'name' => 'Restaurant 1',
            'phone' => '1'
        ]);

        $place = Place::create([
            'name' => 'Place 1',
            'restaurant_id' => '1',
            'status' => $restaurant->id
        ]);

        $wedding = Wedding::create([
            'place_id' => $place->id,
            'date' => '2021-12-13 15:00:00',
            'title' => "Wedding 2022 testing...",
            'pic_name' => "Pic Name test...",
            "ceremony_reception_time" => "06:00-07:00",
            "ceremony_time" => "07:00-08:00",
            "party_reception_time" => "08:00-09:00",
            "party_time" => "09:00-10:00",
            "greeting_message" => "Greeing Message for wedding testing...",
            "thank_you_message" => "Thank you Message for wedding testing...",
            "guest_invitation_response_date" => "2021-12-13",
            "couple_edit_date" => "2021-12-13",
        ]);

        $tableA = TablePosition::create([
            'position' => "TABLE A",
            "status" => "1",
            "amount_chair" => 10,
            "place_id" => $place->id,
            "customer_id" => 0
        ]);

        $tableB = TablePosition::create([
            'position' => "TABLE B",
            "status" => "1",
            "amount_chair" => 10,
            "place_id" => $place->id,
            "customer_id" => 0
        ]);

        Customer::create([
            'username' => '111111111111',
            'full_name' => 'Groom Name',
            'email' => 'groom@mail.com',
            'token' => '12345678',
            'password' => '111111111111',
            'wedding_id' => $wedding->id,
            'role' => "3"
        ]);

        Customer::create([
            'username' => '222222222222',
            'full_name' => 'Bride Name',
            'email' => 'bride@mail.com',
            'token' => '876543121',
            'password' => '222222222222',
            'wedding_id' => $wedding->id,
            'role' => "4"
        ]);

        Customer::create([
            'username' => '333333333333',
            'full_name' => 'GUEST 1',
            'email' => '333333333333@mail.com',
            'token' => '333333333333',
            'password' => '333333333333',
            'wedding_id' => $wedding->id,
            'role' => "5",
            "table_position_id" => $tableA->id
        ]);

        Customer::create([
            'username' => '444444444444',
            'full_name' => 'GUEST 2',
            'email' => '444444444444@mail.com',
            'token' => '444444444444',
            'password' => '444444444444',
            'wedding_id' => $wedding->id,
            'role' => "5",
            "table_position_id" => $tableA->id
        ]);
        
        Customer::create([
            'username' => '555555555555',
            'full_name' => 'GUEST 3',
            'email' => '555555555555@mail.com',
            'token' => '555555555555',
            'password' => '555555555555',
            'wedding_id' => '1',
            'role' => "5",
            "table_position_id" => $tableB->id
        ]);

        Customer::create([
            'username' => '666666666666',
            'full_name' => 'GUEST 4',
            'email' => '666666666666@mail.com',
            'token' => '666666666666',
            'password' => '666666666666',
            'wedding_id' => '1',
            'role' => "5",
            "table_position_id" => $tableB->id
        ]);
    }
}
