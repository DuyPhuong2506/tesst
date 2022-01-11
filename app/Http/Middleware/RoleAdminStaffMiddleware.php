<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Traits\ApiTrait;
use App\Constants\Role;
use JWTAuth;

class RoleAdminStaffMiddleware
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
        $token = $request->bearerToken();
        $exists = \App\Models\UserToken::where('token', $token)->exists();
        if(!Auth::user() || !$exists){
            $token = $request->bearerToken();
            Auth::setToken($token)->invalidate();
            return $this->respondError(
                Response::HTTP_UNAUTHORIZED, 'The token has been blacklisted'
            );
        }
        
        if(Auth::check()){
            if(in_array(Auth::user()->role, [Role::STAFF_ADMIN])){
                return $next($request);
            }
        }
        
        return  $this->respondError(
            Response::HTTP_METHOD_NOT_ALLOWED, 'PERMISSION DENIED, ROLE STAFF'
        );
    }
}
