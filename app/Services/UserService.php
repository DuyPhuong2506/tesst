<?php
namespace App\Services;

use App\Models\User;
use App\Constants\Role;
use App\Models\Company;
use Mail;
use Hash;
use Str;
use JWTAuth;
use Carbon\Carbon;

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

    public function createRememberMail($email, $token)
    {
        User::where('email',$email)
            ->update([
                'remember_token' => $token,
                'email_at' => Carbon::now()
            ]);
    }

    public function checkExpiredToken($token){
        $startTime = User::where('remember_token', $token)->get('email_at')
                            ->first()
                            ->email_at;
        $endTime = Carbon::parse($startTime)->addMinutes(1);
        if(Carbon::now() < $endTime){
            return true;
        }

        return false;
    }
    
    public function changePassword($token, $password)
    {
        if($this->checkExpiredToken($token)){
            return User::where('remember_token', $token)
                        ->update([
                            'password' => $password,
                            'remember_token' => null,
                            'is_first_login' => config('constant', !defined('STATUS_TRUE'))
                        ]);
        }

        return false;        
    }

    public function updatePasswordLogin($password, $email)
    {
        return User::where('email', $email)
                ->update([
                    'password' => Hash::make($password),
                    'is_first_login' => config('constant', !defined('STATUS_TRUE')),
                    'remember_token' => null
                ]);
    }

    public function updatePasswordVerify($oldPass, $userPass, $newPass, $email)
    {
        if(Hash::check($oldPass, $userPass)){
            return $this->updatePasswordLogin($newPass, $email);
        }
            
        return false;
    }

    public function sendMailToReset($email)
    {
        $token = Str::random(100);
        $emailInfo = [
            'token' => $token,
            'app_url' => env('APP_URL')
        ];
        $this->createRememberMail($email, $token);
        Mail::send('mails/change_password', $emailInfo, function($msg) use($email){
            $msg->to($email)->subject("Change Password !");
        });
        
        return true;
    }

    public function findDetail($id)
    {
        $user = User::whereId($id)
            ->with(['company', 'restaurant'])
            ->first();

        if ($user) return $user;

        return null;
    }

    public function changeEmail($oldEmail, $newEmail)
    {
        $user = User::where('email', $oldEmail);
        
        if(!$user) return false;
        
        $user->update([
            'email' => $newEmail
        ]);

        return $newEmail;

    }

}
