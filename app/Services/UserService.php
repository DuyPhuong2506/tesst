<?php
namespace App\Services;

use App\Models\User;
use App\Constants\Role;
use App\Models\Company;

class UserService
{
    public function createAdmin($data)
    {   
        $company = Company::whereIsActive(STATUS_TRUE)->first();
        $companyId = null;
        if($company){
            $companyId = $company->id;
        }
        $role = Role::STAFF_ADMIN;
        $data = array_merge($data, ['role' => $role, 'company_id' => $companyId]);
        $created = User::create($data);
        $detail = $this->findDetail($created->id);

        return $detail;
    }

    public function getAllByRestaurant($restaurant_id)
    {
        return User::staff()
        ->where(['restaurant_id' => $restaurant_id])
        ->where(function($q) {
            $q->whereHas('company' ,function($q){
                $q->whereIsActive(STATUS_TRUE);
            })->orWhere('company_id', null);
        })
        ->with(['company' => function($q){
            $q->select('id', 'name', 'description');
        }])
        ->get();
    }

    public function getStaff($staff_id)
    {
        return $this->findDetail($staff_id);
    }

    public function destroyStaff($staff_id)
    {
        $user = User::staff()->find($staff_id);
        if ($user) return $user->delete();
        return null;
    }

    public function findDetail($id)
    {
        $user = User::whereId($id)
            ->with(['company' => function($q){
                $q->select('id', 'name', 'description');
            }])
            ->first();

        if ($user) return $user;

        return null;
    }
}
