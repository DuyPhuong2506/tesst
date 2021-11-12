<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Hash;
use Illuminate\Support\Facades\Auth;
use App\Services\UserService;
use App\Http\Requests\CreateAdminRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ChangePasswordLogin;
use App\Http\Requests\EmailRequest;
use App\Http\Requests\EmailTokenRequest;
use App\Http\Requests\UpdateStaffInfoRequest;
use App\Http\Requests\UpdateEmailRequest;
use App\Http\Requests\UpdatePasswordVerify;
use App\Http\Requests\NewEmailRequest;
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
                'message' => "Password has been changed !"
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
        $data = $this->userService->findDetail($id);
        $role = Auth::user()->role;

        if(!in_array($role, [Role::SUPER_ADMIN, Role::STAFF_ADMIN])){
            return $this->respondError(Response::HTTP_BAD_REQUEST, 'Failed not found !');
        }

        if($data){
            return $this->respondSuccess($data);
        }

        return $this->respondError(Response::HTTP_BAD_REQUEST, 'Failed to get users info !');
    }

    public function updateSupperAdminEmail(UpdateEmailRequest $request)
    {
        $oldEmail = Auth::user()->email;
        $newEmail = $request->email;
        $data = $this->userService->changeEmail($oldEmail, $newEmail);

        if(Role::SUPER_ADMIN !== Auth::user()->role){
            return $this->respondError(
                Response::HTTP_BAD_REQUEST, 'Your role is not correct !'
            );
        }

        if($data){
            return $this->respondSuccess($data);
        }   

        return $this->respondError(Response::HTTP_BAD_REQUEST, 'Failed to update email super admin email !');
    }

    public function updateSupperAdminPassword(UpdatePasswordVerify $request)
    {
        $oldPassword = $request->verify_password;
        $newPassword = $request->password;
        $userPassword = Auth::user()->password;
        $email = Auth::user()->email;

        if(Role::SUPER_ADMIN !== Auth::user()->role){
            return $this->respondError(
                Response::HTTP_BAD_REQUEST, 'Your role is not correct !'
            );
        }
        
        $status = $this->userService->updatePasswordVerify(
            $oldPassword, $userPassword, $newPassword, $email
        );
        
        if($status){
            Auth::logout();
            return $this->respondSuccess(['message' => 'You have successfully changed password !']);
        }
        else if($status === false){
            return $this->respondError(Response::HTTP_BAD_REQUEST, [
                "password" => ["Old password is not correct !"]
            ]);
        }
            
        return $this->respondError(Response::HTTP_BAD_REQUEST, 'Failed to update super admin password !');
    }

    public function checkExistToken(EmailTokenRequest $request)
    {
        if($this->userService->checkExistToken($request->token)){
            return $this->respondSuccess([
                'message' => 'The token you check is OK in use !'
            ]);
        }

        return $this->respondError(
            Response::HTTP_BAD_REQUEST, 'Failed to check token exist !'
        );
    }

    public function inviteNewAdminStaff(NewEmailRequest $request)
    {
        $role = Auth::user()->role;
        $requestEmail = $request->email;
        
        if($role !== Role::SUPER_ADMIN){
            return $this->respondError(
                Response::HTTP_BAD_REQUEST, 'Not Found !'
            );
        }

        if($this->userService->inviteNewAdminStaff($requestEmail)){
            return $this->respondSuccess([
                'message' => 'You have successfully invite '.$requestEmail
            ]);
        }

        return $this->respondError(
            Response::HTTP_BAD_REQUEST, 'Failed to update super admin password !'
        );
    }

    public function upadateStaffAdmin(UpdateStaffInfoRequest $request)
    {
        $requestData = $request->all();
        $data = $this->userService->staffAdminInfoUpdate($requestData);
        
        if($data){
            return $this->respondSuccess($data);
        }

        return $this->respondError(
            Response::HTTP_BAD_REQUEST, 'Failed to update staff admin !'
        );
    }

}