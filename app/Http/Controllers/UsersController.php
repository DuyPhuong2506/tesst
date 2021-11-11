<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;
use JWTAuthException;
use Hash;
use Illuminate\Support\Facades\Auth;
use App\Services\UserService;
use App\Http\Requests\CreateAdminRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ChangePasswordLogin;
use App\Http\Requests\EmailRequest;
use App\Http\Requests\EmailTokenRequest;
use App\Http\Requests\UpdateStaffInfoRequest;
use App\Http\Requests\UpdateSuperInfoRequest;
use App\Constants\Role;

class UsersController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getUserCurrent(Request $request)
    {
        $user = Auth::user();
        return $this->respondSuccess($user);
    }

    public function createAdmin(CreateAdminRequest $request)
    {
        \DB::beginTransaction();
        try {
            $data = $request->only('username','email','password','phone','address', 'restaurant_id');
            $user = $this->userService->createAdmin($data);
            \DB::commit();
            
            return $this->respondSuccess($user);
        }  catch (\Exception $e) {
            \DB::rollback();
            
            return $this->respondError(Response::HTTP_BAD_REQUEST, $e->getMessage());
        }
    }

    public function getStaffAdmin($id)
    {
        $users = $this->userService->getAllByRestaurant($id);
        if (!empty($users)) return $this->respondSuccess($users);

        return $this->respondError('404','staffs does not exists');
    }

    public function getStaff($id)
    {
        $user = $this->userService->getStaff($id);
        if ($user) return $this->respondSuccess($user);

        return $this->respondError('404','staff does not exists');
    }

    public function destroyStaff($id)
    {
        $user = $this->userService->destroyStaff($id);
        if ($user) return $this->respondSuccess('staff is deleted');

        return $this->respondError(Response::HTTP_NOT_IMPLEMENTED, 'staff cannot delete');
    }

    public function sendEmailResetPassword(EmailRequest $request)
    {
        if($this->userService->sendMailToReset($request->email)){
            return $this->respondSuccess([
                "message"=>"Email has been sent !"
            ]);
        }

        return $this->respondError(Response::HTTP_BAD_REQUEST, 'Failed to send mail !');
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        $data = $request->all();
        $status = $this->userService
                       ->changePassword($data['token'], Hash::make($data['password']));
        if($status){
            return $this->respondSuccess([
                'message' => "Password has been changed !"
            ]);
        }

        return $this->respondError(Response::HTTP_BAD_REQUEST, 'Failed to update password !');
    }

    public function updatePasswordLogin(ChangePasswordLogin $request)
    {
        $email = auth()->userOrFail()->email;
        $password = Hash::make($request->password);
        if($this->userService->updatePasswordLogin($password, $email)){
            return $this->respondSuccess([
                'message'=>"Password has been changed !"
            ]);
        }

        return $this->respondError(Response::HTTP_BAD_REQUEST, 'Failed to update password !');
    }

    public function checkExpiredToken(EmailTokenRequest $request)
    {
        if($this->userService->checkExpiredToken($request->token)){
            return $this->respondSuccess([
                'message' => 'Token is now can use !'
            ]);
        }
        
        return $this->respondError(Response::HTTP_BAD_REQUEST, 'Token is expired !');
    }

    public function getMe()
    {
        $id = Auth::user()->id;
        $role = Auth::user()->role;
        $data = $this->userService->findDetail($id);

        if(in_array($role, [Role::STAFF_ADMIN, Role::SUPER_ADMIN]))
        {
            return $this->respondSuccess($data);
        }
        else
        {
            return $this->respondError(Response::HTTP_BAD_REQUEST, 'Your role is denied !');
        }

        return $this->respondError(Response::HTTP_BAD_REQUEST, 'Failed to get users info !');
    }

    public function updateStaffAdminInfo(UpdateStaffInfoRequest $request)
    {
        $role = Auth::user()->role;
        $data = $request->all();

        if($role === Role::STAFF_ADMIN)
        {
            $user = $this->userService->staffAdminInfoUpdate($data);
        }
        else
        {
            return $this->respondError(Response::HTTP_BAD_REQUEST, 'Your role is denied !');
        }
        
        if($user){
            Auth::logout();
            return $this->respondSuccess('You have successfully logged out.');
        }

        return $this->respondError(Response::HTTP_BAD_REQUEST, 'Failed to update staff admin info !');
    }

    public function updateSuperAdminInfo(UpdateSuperInfoRequest $request)
    {
        $data = $request->all();
        $role = Auth::user()->role;
        $email = Auth::user()->email;
        $userID = Auth::user()->id;
        $userPassword = Auth::user()->password;
        
        if($role === Role::SUPER_ADMIN)
        {
            if(isset($data['old_password']))
            {
                $status = $this->userService->updatePasswordVerify(
                    $data['old_password'],
                    $userPassword,
                    Hash::make($data['password']),
                    $email
                );

                if(!$status) 
                    return $this->respondError(
                        Response::HTTP_BAD_REQUEST, 
                        'Old password is not correct !'
                    );

                Auth::logout();
                return $this->respondSuccess('You have successfully logged out.');
            }
            $this->userService->changeEmail($email, $data['email']);
        }
        else
        {
            return $this->respondError(Response::HTTP_BAD_REQUEST, 'Your role is denied !');
        }
        
        return $this->respondError(Response::HTTP_BAD_REQUEST, 'Failed to update super admin info !');
    }
    

}