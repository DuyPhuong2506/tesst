<?php

use Illuminate\Database\Seeder;
use App\Models\User;

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
            'username' => 'master_wedding',
            'email' => 'master_wedding@test.com',
            'password' => Hash::make(123456),
            'role' => User::ROLE_SUPER_ADMIN
        ]);
    }
}
