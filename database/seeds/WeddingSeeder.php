<?php

use Illuminate\Database\Seeder;
use App\Models\Place;
use App\Models\Wedding;
use App\Models\TablePosition;
use App\Models\Customer;
use App\Models\Restaurant;
use App\Models\EventTimes;
use Illuminate\Support\Str;

class WeddingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $restaurant = Restaurant::create([
            'name' => $faker->name,
            'phone' => $faker->phoneNumber
        ]);

        $place = Place::create([
            'name' => $faker->name,
            'restaurant_id' => $restaurant->id,
            'status' => "1"
        ]);

        $wedding = Wedding::create([
            'place_id' => $place->id,
            'date' => '2021-12-13 15:00:00',
            'title' => $faker->title,
            'pic_name' => $faker->name,
            "ceremony_reception_time" => "06:00-07:00",
            "ceremony_time" => "07:00-08:00",
            "party_reception_time" => "08:00-09:00",
            "party_time" => "09:00-10:00",
            "greeting_message" => $faker->paragraph,
            "thank_you_message" => $faker->paragraph,
            "guest_invitation_response_date" => "2021-12-13",
            "couple_edit_date" => "2021-12-13",
        ]);

        $eventTimes = [];
        for($i = 0; $i < 4; $i++){
            $item = [
                'start' => $faker->time,
                'end' => $faker->time,
                'description' => $faker->name,
                'event_id' => $wedding->id
            ];
            array_push($eventTimes, $item);
        }
        DB::table('wedding_timetable')->insert($eventTimes);
        
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
            'username' => $faker->unique()->userName,
            'full_name' => $faker->name,
            'email' => $faker->email,
            'token' => "87654321",
            'password' => '111111111111',
            'wedding_id' => $wedding->id,
            'role' => "3"
        ]);

        Customer::create([
            'username' => $faker->unique()->userName,
            'full_name' => $faker->name,
            'email' => $faker->email,
            'token' => "12345678",
            'password' => '222222222222',
            'wedding_id' => $wedding->id,
            'role' => "4"
        ]);

        Customer::create([
            'username' => $faker->unique()->userName,
            'full_name' => $faker->name,
            'email' => $faker->email,
            'token' => "123AEQWEQWE",
            'password' => '222222222222',
            'place_id' => $place->id,
            'role' => "6"
        ]);

        Customer::create([
            'username' => $faker->unique()->userName,
            'full_name' => $faker->name,
            'email' => $faker->email,
            'token' => "dfgsdferwer",
            'password' => '222222222222',
            'place_id' => $place->id,
            'role' => "6"
        ]);

        Customer::create([
            'username' => $faker->unique()->userName,
            'full_name' => $faker->name,
            'email' => $faker->email,
            'token' => "fgsfrtyrtyzxd",
            'password' => '222222222222',
            'place_id' => $place->id,
            'role' => "6"
        ]);
        
        Customer::updateOrcreate(
            ['username' => '111122223333'],
            [
                'username' => '111122223333',
                'full_name' => $faker->name,
                'email' => $faker->email,
                'token' => "111122223333",
                'password' => '111122223333',
                'place_id' => $place->id,
                'role' => "5"
            ]
        );

        Customer::create([
            'username' => $faker->unique()->userName,
            'full_name' => $faker->name,
            'email' => $faker->email,
            'token' => "dfgsdferwer",
            'password' => '222222222222',
            'place_id' => $place->id,
            'role' => "5"
        ]);

        Customer::create([
            'username' => $faker->unique()->userName,
            'full_name' => $faker->name,
            'email' => $faker->email,
            'token' => "jhhdfasdqwetyt",
            'password' => '222222222222',
            'place_id' => $place->id,
            'role' => "5"
        ]);
    }
}
