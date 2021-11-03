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
            'password' => 123456,
            'role' => Role::SUPER_ADMIN
        ]);

        User::create([
            'username' => 'admin',
            'email' => 'admin@test.com',
            'password' => 123456,
            // 'restaurant_id' => 1,
            'role' => Role::STAFF_ADMIN
        ]);
    }
}
