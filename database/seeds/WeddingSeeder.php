<?php

use Illuminate\Database\Seeder;
use App\Models\Place;
use App\Models\Wedding;
use App\Models\TablePosition;
use App\Models\Customer;
use App\Models\Restaurant;
use App\Models\EventTimes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class WeddingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function runSeeders()
    {
        $faker = Faker\Factory::create();
        
        //Create Restaurant
        $restaurant = Restaurant::create([
            'name' => $faker->name,
            'phone' => $faker->phoneNumber,
        ]);

        //Create Place
        $place = Place::create([
            'name' => $faker->name,
            'restaurant_id' => $restaurant->id,
            'status' => "1"
        ]);

        //Create Wedding
        $wedding = Wedding::create([
            'place_id' => $place->id,
            'date' => '2021-12-13 15:00:00',
            'title' => $faker->title,
            'pic_name' => $faker->name,
            "ceremony_reception_time" => "06:00-07:00",
            "ceremony_time" => "07:12-12:43",
            "party_reception_time" => "08:00-09:00",
            "party_time" => "13:15-17:11",
            "greeting_message" => $faker->paragraph,
            "thank_you_message" => $faker->paragraph,
            "guest_invitation_response_date" => "2021-12-13",
            "couple_edit_date" => "2021-12-13",
        ]);

        //Create Wedding Time Table
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
        
        //Create TABLE A
        $tableA = TablePosition::create([
            'position' => "TABLE A",
            "status" => "1",
            "amount_chair" => 10,
            "place_id" => $place->id
        ]);

        //Create TABLE B
        $tableB = TablePosition::create([
            'position' => "TABLE B",
            "status" => "1",
            "amount_chair" => 10,
            "place_id" => $place->id
        ]);

        //Create GROOM account
        Customer::create([
            'username' => $faker->unique()->userName,
            'full_name' => $faker->name,
            'email' => $faker->email,
            'token' => "87654321",
            'password' => '111111111111',
            'wedding_id' => $wedding->id,
            'role' => "3"
        ]);

        //Create BRIDE account
        Customer::create([
            'username' => $faker->unique()->userName,
            'full_name' => $faker->name,
            'email' => $faker->email,
            'token' => "12345678",
            'password' => '222222222222',
            'wedding_id' => $wedding->id,
            'role' => "4",
        ]);

        //Create STAGE_TABLE account
        Customer::create([
            'username' => $faker->unique()->userName,
            'full_name' => $faker->name,
            'email' => $faker->email,
            'token' => "123AEQWEQWE",
            'password' => '222222222222',
            'role' => "6",
            'wedding_id' => $wedding->id,
        ]);

        //Create COUPE_TABLE account
        Customer::create([
            'username' => $faker->unique()->userName,
            'full_name' => $faker->name,
            'email' => $faker->email,
            'token' => "dfgsdferwer",
            'password' => '222222222222',
            'role' => "7",
            'wedding_id' => $wedding->id,
        ]);

        //Create SPEECH_TABLE account
        Customer::create([
            'username' => $faker->unique()->userName,
            'full_name' => $faker->name,
            'email' => $faker->email,
            'token' => "fgsfrtyrtyzxd",
            'password' => '222222222222',
            'role' => "8",
            'wedding_id' => $wedding->id,
        ]);
        
        //Create NORMAL_TABLE account
        Customer::create([
            'username' => $faker->unique()->userName,
            'full_name' => $faker->name,
            'email' => $faker->email,
            'token' => "111122223333",
            'password' => '111122223333',
            'role' => "9",
            "wedding_id" => $wedding->id,
        ]);

        // Create GUEST 1
        $guest1 = Customer::create([
            'username' => 'qqqqqqqqqqqq',
            'full_name' => $faker->name,
            'email' => $faker->email,
            'token' => "qqqqqqqqqqqq",
            'password' => 'qqqqqqqqqqqq',
            'role' => "5",
            'wedding_id' => $wedding->id
        ]);

        // Create GUEST 2
        $guest2 = Customer::create([
            'username' => 'wwwwwwwwwwww',
            'full_name' => $faker->name,
            'email' => $faker->email,
            'token' => "wwwwwwwwwwww",
            'password' => 'wwwwwwwwwwww',
            'role' => "5",
            'wedding_id' => $wedding->id
        ]);

        // Create GUEST 3
        $guest3 = Customer::create([
            'username' => 'eeeeeeeeeeee',
            'full_name' => $faker->name,
            'email' => $faker->email,
            'token' => "eeeeeeeeeeee",
            'password' => 'eeeeeeeeeeee',
            'role' => "5",
            'wedding_id' => $wedding->id
        ]);

        // Create GUEST 4
        $guest4 = Customer::create([
            'username' => 'dddddddddddd',
            'full_name' => $faker->name,
            'email' => $faker->email,
            'token' => "dddddddddddd",
            'password' => 'dddddddddddd',
            'role' => "5",
            'wedding_id' => $wedding->id
        ]);

        
        $tableA->customers()->attach([$guest1->id],[
            'chair_name' => $faker->name,
            'status' => '1'
        ]);

        $tableA->customers()->attach([$guest2->id],[
            'chair_name' => $faker->name,
            'status' => '1'
        ]);

        $tableB->customers()->attach([$guest3->id],[
            'chair_name' => $faker->name,
            'status' => '1'
        ]);

        $tableB->customers()->attach([$guest4->id],[
            'chair_name' => $faker->name,
            'status' => '1'
        ]);

        return true;
    }

    public function run()
    {
        DB::beginTransaction();
        try {
            if($this->runSeeders()){
                DB::commit();
                echo "Dump data successfully !";
            }else{
                DB::rollback();
                echo "Failed to dump wedding data!";
            }
        } catch (\Throwable $th) {
            DB::rollback();
            echo "Failed to dump wedding data!";
        }
    }
}
