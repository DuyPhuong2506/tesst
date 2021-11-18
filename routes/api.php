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
Route::post('auth/register', 'AuthController@register');
Route::post('auth/login', 'AuthController@login');
Route::post('auth/customer/login', 'AuthCustomerController@login');
Route::post('auth/table-account/login', 'AuthTableAccountController@login');

#API CHANGE PASSWORD
Route::post('forgot-password','UsersController@sendEmailResetPassword');
Route::post('change-password','UsersController@updatePassword');
Route::post('check-token-expired','UsersController@checkExpiredToken');
Route::post('check-token-exist','UsersController@checkExistToken');

Route::group(['middleware' => ['jwtAuth']], function () {

    /* Role Admin (STAFF ADMIN & SUPER_ADMIN)*/
    Route::group(['middleware' => 'auth.admin'], function(){
        Route::prefix('users')->group(function () {
            Route::get('/get-me','UsersController@getMe');
            Route::put('/password-verify/update', 'UsersController@updatePasswordWithVerify');
        });
    });
    
    /* Role Staff Admin */
    Route::group(['middleware' => 'auth.admin_staff'], function(){
        Route::prefix('event')->group(function () {
            Route::post('/create-time-table', 'EventsController@createTimeTable');
            Route::get('/delete-time-table/{id}', 'EventsController@deleteTimeTable');
            Route::post('/update-thank-msg', 'EventsController@updateThankMsg');
        });
        Route::resource('event','EventsController');

        Route::prefix('users')->group(function () {
            Route::put('/staff-admin/create-or-update', 'UsersController@upadateStaffAdmin');
        });
    });

    /* Role Super Admin */
    Route::group(['middleware' => 'auth.super_admin'], function(){
        Route::prefix('users')->group(function () {
            Route::put('/super-admin/email/update', 'UsersController@updateSupperAdminEmail');
            Route::post('/super-admin/invite-admin-staff', 'UsersController@inviteNewAdminStaff');
        });
    });

    Route::post('auth/logout', 'AuthController@logout');
    Route::post('admin/create','UsersController@createAdmin');
    Route::resource('places', 'PlacesController');
    Route::get('places-get-pre-signed', 'PlacesController@getPreSigned')->name('get.getPreSigned');
    Route::resource('restaurants','RestaurantsController');

    /** route staffs */
    Route::get('restaurants-staffs','UsersController@getStaffAdmin');
    Route::get('staff','UsersController@getListStaff');
    Route::get('staff/{user_id}','UsersController@getStaff');
    Route::delete('staff/{user_id}','UsersController@destroyStaff');
   
});
Route::get('agora/get-token','AgoraController@generateToken')->middleware('cors');
Route::get('/send-mail', function () {
    Mail::to('anhpmt@bap.jp')->send(new SendMail()); 
    return 'A message has been sent to Mailtrap!';
});