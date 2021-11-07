<?php
namespace App\Services;

use App\Models\User;
use App\Constants\Role;
use App\Models\Company;
use Mail;
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

    public function createRememberMail($email, $token, $email_at)
    {
        User::where('email',$email)
            ->update([
                'remember_token' => $token,
                'email_at' => $email_at
            ]);
    }
    
    public function changePassword($token, $password)
    {
        $startTime = User::where('remember_token', $token)->get('email_at')
                            ->first()
                            ->email_at;
        $endTime = Carbon::now();
        if($endTime->diffInSeconds($startTime) < config('mail.reset_password_expired')){
            return User::where('remember_token', $token)
                        ->update([
                            'password' => $password,
                            'remember_token' => null,
                            'is_first_login' => '1'
                        ]);
        }

        return false;        
    }

    public function updatePasswordLogin($password, $email)
    {
        return User::where('email', $email)
                ->update([
                    'password' => $password,
                    'is_first_login' => '1',
                    'remember_token' => null
                ]);
    }

    public function sendMailToReset($email)
    {
        $token = Str::random(100);
        $emailInfo = [
            'token' => $token,
            'app_url' => env('APP_URL')
        ];
        $this->createRememberMail($email, $token, Carbon::now()->toDateTimeString());
        Mail::send('mails/change_password', $emailInfo, function($msg) use($email){
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
