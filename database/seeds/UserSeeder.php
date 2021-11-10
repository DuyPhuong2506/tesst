<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Constants\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'super_admin',
            'email' => 'super_admin@test.com',
            'password' => 12345678,
            'role' => Role::SUPER_ADMIN
        ]);

        User::create([
            'username' => 'admin',
            'email' => 'admin@test.com',
            'password' => 12345678,
            // 'restaurant_id' => 1,
            'role' => Role::STAFF_ADMIN
        ]);

        User::create([
            'username' => 'danhdat',
            'email' => 'datd@bap.jp',
            'password' => 12345678,
            'role' => Role::SUPER_ADMIN,
            'restaurant_id' => 1,
            'company_id' => 1,
            'ceremony_name' => 'Ceremony',
            'charge_name' => 'Charge Name',
            'portal_code' => '200'
        ]);
    }
}
