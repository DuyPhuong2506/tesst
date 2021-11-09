<?php

use Illuminate\Http\Request;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/test','UsersController@get');
Route::post('auth/register', 'AuthController@register');
Route::post('auth/login', 'AuthController@login');
Route::post('auth/customer/login', 'AuthCustomerController@login');
Route::post('auth/table-account/login', 'AuthTableAccountController@login');

#API CHANGE PASSWORD
Route::post('forgot-password','UsersController@sendEmailResetPassword');
Route::post('change-password','UsersController@updatePassword');
Route::post('check-token-expired','UsersController@checkExpiredToken');


Route::group(['middleware' => ['jwtAuth']], function () {

    /**First login and change password**/
    Route::post('change-password-login', 'UsersController@updatePasswordLogin');

    /**Wedding event API**/
    Route::resource('event','EventsController');

    /**My Page - Detail Account**/
    Route::get('users/get-me','UsersController@getMe');
    
    Route::post('auth/logout', 'AuthController@logout');
    Route::post('admin/create','UsersController@createAdmin');
    Route::resource('places', 'PlacesController');
    Route::resource('restaurants','RestaurantsController');

    /** route staffs */
    Route::get('restaurants/{restaurant_id}/staffs','UsersController@getStaffAdmin');
    Route::get('staff','UsersController@getListStaff');
    Route::get('staff/{user_id}','UsersController@getStaff');
    Route::delete('staff/{user_id}','UsersController@destroyStaff');

});
Route::get('agora/get-token','AgoraController@generateToken')->middleware('cors');
Route::get('/send-mail', function () {
    Mail::to('anhpmt@bap.jp')->send(new SendMail()); 
    return 'A message has been sent to Mailtrap!';
});