<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Traits\ApiTrait;
use JWTAuth;

class VerifySuperAdmin
{
    use ApiTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->role == \App\Models\User::ROLE_SUPER_ADMIN) return $next($request);
        
        return  $this->respondError(Response::HTTP_METHOD_NOT_ALLOWED, ' PERMISSION DENIED');
    }
}

