<?php
namespace App\Services;

use App\Models\User;
use App\Constants\Role;
use App\Models\Company;
use App\Models\Restaurant;
use App\Models\Customer;
use App\Jobs\SendMailResetPasswordJob;
use App\Jobs\SendMailInviteStaffJob;
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

    public function getAllByRestaurant($request)
    {
        $orderBy = isset($request['order_by']) ? explode('|', $request['order_by']) : [];
        $keyword = !empty($request['keyword']) ? escape_like($request['keyword']) : null;
        $paginate = !empty($request['paginate']) ? $request['paginate'] : PAGINATE;

        $staffs = User::staff()
            ->when(count($orderBy) > 1, function($q) use ($orderBy) {
                $q->orderBy($orderBy[0], $orderBy[1]);
            })
            ->when(!empty($keyword), function($q) use ($keyword) {
                $q->where(function($q) use ($keyword){
                    $q->where('email', 'like', '%'.$keyword.'%')
                        ->orWhereHas('restaurant', function($q) use ($keyword) {
                            $q->where('name', 'like', '%'.$keyword.'%')
                                ->orWhere('company_name', 'like', '%'.$keyword.'%');
                        });
                });
            })
            ->where(function($q) {
                $q->whereHas('company' ,function($q){
                    $q->whereIsActive(STATUS_TRUE);
                })->orWhere('company_id', null);
            })
            ->with(['company' => function($q){
                $q->select('id', 'name', 'description');
            }])
            ->with(['restaurant' => function($q){
                $q->select('id', 'name', 'company_name');
            }])
            ->orderBy('created_at', 'desc');

            if($paginate != PAGINATE_ALL){
               $staffs = $staffs->paginate($paginate);
            } else {
                $staffs = $staffs->get();
            }
        return $staffs;
    }

    public function getStaff($staff_id)
    {
        return $this->findDetail($staff_id);
    }

    public function destroyStaff($staff_id)
    {
        $user = User::staff()->find($staff_id);
        if ($user){
            User::whereId($staff_id)
                ->with(['restaurant' => function($q){
                    $q->with(['places' => function($q){
                        $q->with(['weddings' => function($q){
                            $q->with(['customers' => function($q){
                                $q->delete();
                            }]);
                        }]);
                    }]);
                }])
                ->first();

            return $user->delete();
        }

        return null;
    }

    public function existUser($id)
    {
        $user = User::staff()->where('id', $id)->exists();
        if($user){
            return true;
        }
        
        return false;
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
        $endTime = Carbon::parse($startTime)->addHours(1);
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
                            'remember_token' => null
                        ]);
        }

        return false;        
    }

    public function updatePasswordLogin($password, $email)
    {
        return User::where('email', $email)
                ->update([
                    'password' => $password,
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
            'app_url' => env('ADMIN_URL')
        ];
        $this->createRememberMail($email, $token);
        $resetPasswordJob = new SendMailResetPasswordJob($email, $emailInfo);
        dispatch($resetPasswordJob);

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

    public function getMeCustomer($id)
    {
        return Customer::whereId($id)->first();
    }

    public function inviteNewAdminStaff($email)
    {
        $token = Str::random(100);
        $emailInfo = [
            'app_url' => env('ADMIN_URL'),
            'token' => $token
        ];

        User::create([
            'email' => $email,
            'remember_token' => $token,
            'role' => Role::STAFF_ADMIN,
            'username' => random_str(20),
            'password' => random_str(200)
        ]);

        $inviteStaffJob = new SendMailInviteStaffJob($email, $emailInfo);
        dispatch($inviteStaffJob);

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
            'address_2' => $data['address_2'],
            'company_name' => $data['company_name'],
            'guest_invitation_response_num' => $data['guest_invitation_response_num'],
            'couple_edit_num' => $data['couple_edit_num']
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

        /*
        | If STAFF_ADMIN update info, we look it IS FIRST LOGIN
        */

        if($user->role === Role::STAFF_ADMIN){
            $user->update([
                'is_first_login' => config('constant', !defined('STATUS_TRUE'))
            ]);
        }

        return $this->findDetail($userId);
    }
    
}
