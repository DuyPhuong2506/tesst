<?php

use Illuminate\Database\Seeder;
use App\Models\CustomerTask;

class CustomerTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CustomerTask::create([
            'name' => 'MC and playing game',
            'description' => 'The game playing in the wedding',
        ]);

        CustomerTask::create([
            'name' => 'Guest reception moment',
            'description' => 'The time that couple welcome guest',
        ]);

        CustomerTask::create([
            'name' => 'Speaking',
            'description' => "The couple's family speaking",
        ]);
    }
}
