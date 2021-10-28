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
        $data = $request->only('username','email','password','phone','address', 'restaurant_id');
        $user = $this->userService->createAdmin($data);
        return $this->respondSuccess($user);
    }

    public function getStaffAdmin($id)
    {
        
        $users = $this->userService->getAllByRestaurant($id);
        if (empty($users)) return $this->respondSuccess($users);

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

        return $this->respondError(Response::HTTP_NOT_IMPLEMENTED,'staff cannot delete');
    }
}
