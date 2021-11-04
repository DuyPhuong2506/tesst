<?php
namespace App\Services;

use App\Models\User;
use App\Constants\Role;
use App\Models\Company;
use Mail;
use Str;

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

    public function updateRememberToken($email,$token){
        User::where('email',$email)
            ->update(['remember_token'=>$token]);
    }  
    
    public function changePassword($email,$password){
        return User::where('email',$email)
            ->update([
                'password'=>$password,
                'remember_token'=>null
            ]);
    }

    public function sendMailToReset($email){
        $token = Str::random(100);
        $emailInfo = [
            'token' => $token,
            'email_address' => $email,
            'app_url' => env('APP_URL')
        ];
        $this->updateRememberToken($email,$token);
        Mail::send('mails/changepassword',$emailInfo, function($msg) use($email){
            $msg->to($email)->subject("Change Password !");
        });
        return true;
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
