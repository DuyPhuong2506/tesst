<?php

use Illuminate\Database\Seeder;
use App\Constants\Role;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::create([
            'username' => 'couple',
            'email' => 'couple@test.com',
            'password' => 123456,
            'wedding_id' => 1,
            'role' => Role::COUPLE
        ]);

        Customer::create([
            'username' => 'guest',
            'email' => 'guest@test.com',
            'password' => 123456,
            'wedding_id' => 1,
            'role' => Role::GUEST
        ]);
    }
}
