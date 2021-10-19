<?php
namespace App\Services;

use App\Models\User;

class UserService
{

    public function createAdmin($data)
    {
        $role = User::ROLE_ADMIN;
        $data = array_merge($data, ['role' => $role]);
        return User::create($data);
    }
}
