<?php

namespace App\Http\Controllers;

use App\Constants\Common;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\UserTokenService;
use App\Constants\Role;
use JWTAuth;
use JWTAuthException;
use Hash;
use Carbon\Carbon;

class AuthController extends Controller
{
    private $user;
    protected $userTokenService;

    public function __construct(
        User $user, 
        UserTokenService $userTokenService
    ){
        \Config::set('jwt.user', User::class);
        \Config::set('auth.providers', ['users' => [
                'driver' => 'eloquent',
                'model' => User::class,
            ]]);
        $this->user = $user;
        $this->userTokenService = $userTokenService;
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
                return $this->respondError(Response::HTTP_BAD_REQUEST, __('messages.login.admin.login_fail'));
            }
        } catch (JWTAuthException $e) {
            return $this->respondError(Response::HTTP_BAD_REQUEST, 'failed_to_create_token');
        }

        $auth = Auth::user();
        $this->userTokenService->createToken($auth->id, $token);
        $tempStatus = $auth->is_first_login;

        $auth->update([
            'lasted_login' => Carbon::now()
        ]);
        
        if($auth->role === Role::SUPER_ADMIN){
            $auth->update([
                'is_first_login' => Common::STATUS_TRUE
            ]);
        }
        
        $data = $this->respondWithToken($token);
        $data['info']['is_first_login'] = $tempStatus;
        return $this->respondSuccess($data);
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
            $token = $request->bearerToken();
            $this->userTokenService->destroyToken($token);
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
