<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Traits\ApiTrait;
use JWTAuth;

class RoleSuperAdminMiddleware
{
    use ApiTrait;
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request params data
     * @param \Closure                 $next    params data
     *
     * @return mixed return next
     */
    public function handle($request, Closure $next)
    {
        if(!Auth::user()) return $this->respondError(Response::HTTP_NOT_FOUND, "404 - Page Not Found");
        
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->role == \App\Constants\Role::SUPER_ADMIN) return $next($request);
        
        return  $this->respondError(Response::HTTP_METHOD_NOT_ALLOWED, 'PERMISSION DENIED, ROLE SUPER ADMIN');
    }
}
