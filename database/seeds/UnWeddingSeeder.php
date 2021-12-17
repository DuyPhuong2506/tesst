<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnWeddingSeeder extends Seeder
{
    public function runSeeder()
    {
        DB::table('customers')->delete();
        DB::table('weddings')->delete();
        DB::table('places')->delete();
        DB::table('table_positions')->delete();
        DB::table('restaurants')->delete();

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
            if($this->runSeeder()){
                DB::commit();
                echo "Data wedding delete successfully !";
            }
        } catch (\Throwable $th) {
            DB::rollback();
            echo "Data wedding delete failed !";
        }
    }
}
