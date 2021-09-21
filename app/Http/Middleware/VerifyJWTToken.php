<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Traits\ApiTrait;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use JWTAuth;


class VerifyJWTToken
{
    use ApiTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        }catch (JWTException $e) {
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return $this->respondError($e->getStatusCode(), 'token_expired');
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return $this->respondError($e->getStatusCode(), 'token_invalid' );
            }else{
                return $this->respondError(Response::HTTP_UNAUTHORIZED, 'Token is required' );
            }
        }
        return $next($request);
    }
}
