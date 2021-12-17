<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Wedding;
use App\Models\Customer;
use App\Models\Place;

class UnWeddingSeeder extends Seeder
{
    public function runSeeders()
    {
        $wedding = Wedding::where('ceremony_time' , '07:00-08:00')
                          ->where('party_time', '09:00-10:00')
                          ->firstOrFail();

        $weddingId = $wedding->id;
        $placeId = $wedding->place_id;

        $place = Place::find($placeId);

        $restaurantId = $place->restaurant_id;

        DB::table('customers')->where('wedding_id', $weddingId)->delete();
        DB::table('table_positions')->where('place_id', $placeId)->delete();
        DB::table('weddings')->where('id', $weddingId)->delete();
        DB::table('places')->where('id', $placeId)->delete();
        DB::table('restaurants')->where('id', $restaurantId)->delete();

        return true;
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            if($this->runSeeders()){
                DB::commit();
                echo "Wedding delete successfully !";
            }else{
                DB::rollback();
                echo "Failed to delete wedding data!";
            }
        } catch (\Throwable $th) {
            DB::rollback();
            echo "Failed to delete wedding data!";
        }
    }
}
