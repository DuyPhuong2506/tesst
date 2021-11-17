<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use App\Constants\Role;
use JWTAuth;
use JWTAuthException;
use Hash;
use Carbon\Carbon;

class AuthController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        \Config::set('jwt.user', User::class);
        \Config::set('auth.providers', ['users' => [
                'driver' => 'eloquent',
                'model' => User::class,
            ]]);
        $this->user = $user;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->user->create([
            'username' => $request->get('username'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password'))
        ]);
        return $this->respondSuccess($user);
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $token = null;
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->respondError(Response::HTTP_BAD_REQUEST, __('messages.login.login_fail'));
            }
        } catch (JWTAuthException $e) {
            return $this->respondError(Response::HTTP_BAD_REQUEST, 'failed_to_create_token');
        }

        if(Auth::user()->role === Role::SUPER_ADMIN){
            Auth::user()->update([
                'is_first_login' => Auth::user()->is_first_login + 1,
                'lasted_login' => Carbon::now()
            ]);
        }
        
        return $this->respondSuccess($this->respondWithToken($token));
    }

    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */
    public function logout(Request $request)
    {
        //$this->validate($request, ['token' => 'required']);
        
        try {
            auth()->logout();
            return $this->respondSuccess([
                'message' => 'You have successfully logged out.'
            ]);
        } catch (JWTException $e) {
            return $this->respondError(Response::HTTP_BAD_REQUEST, 'Failed to logout, please try again.');
        }
    }

    public function refresh()
    {
        return response(JWTAuth::getToken(), Response::HTTP_OK);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' =>  auth()->factory()->getTTL() * 60,
            'info' => \Auth::user(),
        ];
    }
}
