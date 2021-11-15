<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use App\Constants\Role;
use JWTAuth;
use JWTAuthException;
use Hash;

class AuthCustomerController extends Controller
{
    private $customer;

    public function __construct(Customer $customer)
    {
        \Config::set('jwt.user', Customer::class);
        \Config::set('auth.providers', ['users' => [
                'driver' => 'eloquent',
                'model' => Customer::class,
            ]]);
        $this->customer = $customer;
    }


    /**
     * Get a JWT token via given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        $customer = Customer::where('username', $username)
                            ->where('password', $password)
                            ->first();

        try {
            if(!$token = JWTAuth::fromUser($customer)){
                return $this->respondError(Response::HTTP_BAD_REQUEST, 'invalid_email_credentials');
            }
        } catch (\Throwable $th) {
            return $this->respondError(Response::HTTP_BAD_REQUEST, 'failed_to_create_token');
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
            return $this->respondSuccess('You have successfully logged out.');
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
