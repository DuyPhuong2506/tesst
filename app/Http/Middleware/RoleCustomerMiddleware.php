<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Traits\ApiTrait;
use App\Constants\Role;

class RoleCustomerMiddleware
{
    use ApiTrait;

    public function handle($request, Closure $next)
    {
        if(!Auth::guard('customer')->user()){
            $token = $request->bearerToken();
            Auth::setToken($token)->invalidate();
            
            return $this->respondError(Response::HTTP_UNAUTHORIZED, 'The token has been blacklisted');
        }
        
        $auth = Auth::guard('customer');
        if($auth->check()){
            if(in_array($auth->user()->role, [Role::GROOM, Role::BRIDE, Role::GUEST, Role::STAGE_TABLE, Role::COUPE_TABLE, Role::SPEECH_TABLE, Role::NORMAL_TABLE])){
                return $next($request);
            } 
        }
        
        return  $this->respondError(Response::HTTP_METHOD_NOT_ALLOWED, 'PERMISSION DENIED, ROLE GUEST');
    }
}