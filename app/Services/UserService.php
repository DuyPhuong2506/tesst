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

    public function existEmail($email){
        return (User::where('email',$email)->count() > 0) ? true : false;
    }

    public function updateRememberToken($email,$token){
        User::where('email',$email)
        ->update(['remember_token'=>$token]);
    }

    public function checkRememberToken($email, $form_token){
        $db_token = User::where('email',$email)
        ->where('remember_token',$form_token)
        ->get('remember_token')
        ->first()->remember_token;
        return ($db_token === $form_token) ? true : false;
    }

    public function changePassword($email,$password){
        return User::where('email',$email)
        ->update([
            'password'=>$password,
            'remember_token'=>null
        ]);
    }

}
