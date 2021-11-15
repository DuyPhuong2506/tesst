<?php
namespace App\Services;

use App\Models\User;
use App\Constants\Role;
use App\Models\Company;
use App\Models\Restaurant;
use Mail;
use Hash;
use Str;
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

    public function getAllByRestaurant()
    {
        return User::staff()
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

    public function checkExistToken($token)
    {
        if(User::where('remember_token', $token)){
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
                    'password' => $password,
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

    public function inviteNewAdminStaff($email, $inviterMail)
    {
        if($email === $inviterMail){
            return false;
        }

        $token = Str::random(100);
        $emailInfo = [
            'app_url' => env('APP_URL'),
            'token' => $token
        ];

        User::updateOrCreate(
            ['email' => $email],
            [
                'email' => $email,
                'remember_token' => $token,
                'role' => Role::STAFF_ADMIN,
                'username' => random_str(20),
                'password' => random_str(200)
            ]
        );

        Mail::send('mails/admin_staff_invite', $emailInfo, function($msg) use($email){
            $msg->to($email)->subject("Invite Registry Account Admin Staff!");
        });

        return true;
    }

    public function checkBelongToRestaurant($id)
    {
        $user = User::where('id', $id)
                    ->get('restaurant_id')
                    ->first()->restaurant_id;
        if($user !== null){
            return true;
        }

        return false;
    }

    public function staffAdminInfoUpdate($data, $userId)
    {
        $user = User::find($userId);
        if(!$user) return false;

        $dataRestaurant = [
            'name' => $data['restaurant_name'],
            'phone' => $data['phone'],
            'contact_name' => $data['contact_name'],
            'contact_email' => $data['contact_email'],
            'post_code' => $data['post_code'],
            'address_1' => $data['address_1'],
            'address_2' => $data['address_2']
        ];

        /*
        | Check user is belong to restaurant ???
        | If user IS NOT belong to restaurant, we CREATE new restaurant
        | Else we UPDATE restaurant info where user belong to
        */

        if(!$this->checkBelongToRestaurant($userId)){
            $restaurant = Restaurant::create($dataRestaurant);
            $user->restaurant_id = $restaurant->id;
            $user->save();
        }else{
            $user->restaurant()->update($dataRestaurant);
        }

        $user->company()->update([
            'name' => $data['company_name']
        ]);

        $user->update([
            'created_at' => $data['created_at'],
            'company_name' => $data['company_name']
        ]);

        /*
        | If STAFF_ADMIN update info, we look it IS FIRST LOGIN
        */

        if($user->role === Role::STAFF_ADMIN){
            $user->update([
                'created_at' => $data['created_at'],
                'is_first_login' => config('constant', !defined('STATUS_TRUE'))
            ]);
        }

        return $this->findDetail($userId);
    }
    
}
