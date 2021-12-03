<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Traits\ApiTrait;
use App\Constants\Role;

class RoleCoupleMiddleware
{
    use ApiTrait;

    public function handle($request, Closure $next)
    {
        if(!Auth::guard('customer')->user()) return $this->respondError(Response::HTTP_NOT_FOUND, "404 - Page Not Found");
        
        $auth = Auth::guard('customer');
        if($auth->check()){
            if(in_array($auth->user()->role, [Role::GROOM, Role::BRIDE])){
                return $next($request);
            } 
        }
        
        return  $this->respondError(Response::HTTP_METHOD_NOT_ALLOWED, 'PERMISSION DENIED, ROLE COUPLE');
    }
}
