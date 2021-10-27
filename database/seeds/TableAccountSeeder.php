<?php

use Illuminate\Database\Seeder;
use App\Models\TableAccount;
use App\Constants\Role;

class TableAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TableAccount::create([
            'username' => 'table1',
            'password' => 123456,
            'place_id' => 1,
            'role' => Role::TABLE_ACCOUNT
        ]);
    }
}
