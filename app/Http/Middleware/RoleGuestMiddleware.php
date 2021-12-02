<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Traits\ApiTrait;
use Illuminate\Support\Facades\Auth;
use App\Constants\Role;

class RoleGuestMiddleware
{
    use ApiTrait;

    public function handle($request, Closure $next)
    {
        if(!Auth::user()) return $this->respondError(Response::HTTP_NOT_FOUND, "404 - Page Not Found");
        
        $auth = Auth::guard('customer');
        if($auth->check()){
            if(in_array($auth->user()->role, [Role::GUEST])){
                return $next($request);
            } 
        }
        
        return  $this->respondError(Response::HTTP_METHOD_NOT_ALLOWED, 'PERMISSION DENIED, ROLE GUEST');
    }
}
