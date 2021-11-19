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

    public function getStaffAdmin(Request $request)
    {
        $users = $this->userService->getAllByRestaurant($request);
        if (!empty($users)) return $this->respondSuccess($users);

        return $this->respondError(Response::HTTP_BAD_REQUEST, __('messages.user.list_fail'));
    }

    public function getStaff($id)
    {
        $user = $this->userService->getStaff($id);
        if ($user) return $this->respondSuccess($user);

        return $this->respondError(Response::HTTP_BAD_REQUEST, __('messages.user.detail_fail'));
    }

    public function destroyStaff($id)
    {
        $user = $this->userService->destroyStaff($id);
        if ($user) return $this->respondSuccess(['message', __('messages.admin_staff.delete_success')]);

        return $this->respondError(Response::HTTP_NOT_IMPLEMENTED, __('messages.admin_staff.delete_fail'));
    }

    public function sendEmailResetPassword(EmailRequest $request)
    {
        if($this->userService->sendMailToReset($request->email)){
            return $this->respondSuccess([
                "message" => __('messages.mail.send_success')
            ]);
        }

        return $this->respondError(Response::HTTP_BAD_REQUEST, __('messages.mail.send_fail'));
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        $data = $request->all();
        $status = $this->userService
                       ->changePassword($data['token'], Hash::make($data['password']));
        if($status){
            return $this->respondSuccess([
                'message' =>  __('messages.user.password_success')
            ]);
        }

        return $this->respondError(Response::HTTP_BAD_REQUEST, __('messages.user.password_fail'));
    }

    public function checkExpiredToken(EmailTokenRequest $request)
    {
        if($this->userService->checkExpiredToken($request->token)){
            return $this->respondSuccess([
                'message' => __('messages.user.token_success')
            ]);
        }
        
        return $this->respondError(Response::HTTP_BAD_REQUEST, __('messages.user.token_fail'));
    }

    public function getMe()
    {
        $id = Auth::user()->id;
        $data = $this->userService->findDetail($id);
        $role = Auth::user()->role;

        if($data){
            return $this->respondSuccess($data);
        }

        return $this->respondError(Response::HTTP_BAD_REQUEST, __('messages.user.detail_fail'));
    }

    public function updateSupperAdminEmail(UpdateEmailRequest $request)
    {
        $oldEmail = Auth::user()->email;
        $newEmail = $request->email;
        $data = $this->userService->changeEmail($oldEmail, $newEmail);

        if($data){
            return $this->respondSuccess($data);
        }   

        return $this->respondError(Response::HTTP_BAD_REQUEST, __('messages.user.update_fail'));
    }

    public function updatePasswordWithVerify(UpdatePasswordVerify $request)
    {
        $oldPassword = $request->verify_password;
        $newPassword = $request->password;
        $userPassword = Auth::user()->password;
        $email = Auth::user()->email;
        
        $status = $this->userService->updatePasswordVerify(
            $oldPassword, $userPassword, Hash::make($newPassword), $email
        );
        
        if($status){
            Auth::logout();
            return $this->respondSuccess([
                'message' => __('messages.user.password_success')
            ]);
        }
        else if($status === false){
            return $this->respondError(Response::HTTP_BAD_REQUEST, [
                "password" => [__('messages.user.password_verify_fail')]
            ]);
        }
            
        return $this->respondError(Response::HTTP_BAD_REQUEST, __('messages.user.password_fail'));
    }

    public function checkExistToken(EmailTokenRequest $request)
    {
        if($this->userService->checkExistToken($request->token)){
            return $this->respondSuccess([
                'message' => __('messages.user.token_success')
            ]);
        }

        return $this->respondError(
            Response::HTTP_BAD_REQUEST, __('messages.user.token_fail')
        );
    }

    public function inviteNewAdminStaff(NewEmailRequest $request)
    {
        $role = Auth::user()->role;
        $requestEmail = $request->email;
        if($this->userService->inviteNewAdminStaff($requestEmail)){
            return $this->respondSuccess([
                'message' => __('messages.mail.send_success')
            ]);
        }
        return $this->respondError(
            Response::HTTP_BAD_REQUEST, __('messages.user.existed')
        );
    }

    public function upadateStaffAdmin(UpdateStaffInfoRequest $request)
    {
        $userId = Auth::user()->id;
        $requestData = $request->all();
        $data = $this->userService->staffAdminInfoUpdate($requestData, $userId);
        
        if($data){
            return $this->respondSuccess($data);
        }

        return $this->respondError(
            Response::HTTP_BAD_REQUEST, __('messages.user.update_fail')
        );
    }

}