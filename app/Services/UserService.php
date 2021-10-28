<?php
namespace App\Services;

use App\Models\User;
use App\Constants\Role;

class UserService
{
    public function createAdmin($data)
    {
        $role = Role::STAFF_ADMIN;
        $data = array_merge($data, ['role' => $role]);
        return User::create($data);
    }
}
