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

    public function getAllByRestaurant($restaurant_id)
    {
        return User::staff()->where(['restaurant_id' => $restaurant_id])->get();
    }

    public function getStaff($staff_id)
    {
        return User::staff()->find($staff_id);
    }

    public function destroyStaff($staff_id)
    {
        $user = User::staff()->find($staff_id);
        if ($user) return $user->delete();
        return null;
    }
}
