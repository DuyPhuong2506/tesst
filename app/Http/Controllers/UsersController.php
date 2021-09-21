<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use JWTAuthException;
use Hash;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{

    public function getUserCurrent(Request $request)
    {
        $user = Auth::user();
        return $this->respondSuccess($user);
    }
}
