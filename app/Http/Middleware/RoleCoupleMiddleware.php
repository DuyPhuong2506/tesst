<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Traits\ApiTrait;
use Illuminate\Support\Facades\Auth;
use App\Constants\Role;

class RoleCoupleMiddleware
{
    use ApiTrait;

    public function handle($request, Closure $next)
    {
        $auth = Auth::guard('customer');
        if($auth->check()){
            if(in_array($auth->user()->role, [Role::GROOM, Role::BRIDE])){
                return $next($request);
            } 
        }
        
        return  $this->respondError(Response::HTTP_METHOD_NOT_ALLOWED, 'PERMISSION DENIED, ROLE COUPLE');
    }
}
