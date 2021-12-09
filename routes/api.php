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
Route::get('/dump-customer-token', 'EventsController@dumpCustomerToken');

Route::post('auth/register', 'AuthController@register');
Route::post('auth/login', 'AuthController@login');
Route::post('auth/customer/login', 'AuthCustomerController@login');
Route::post('auth/table-account/login', 'AuthTableAccountController@login');

#API CHANGE PASSWORD
Route::post('forgot-password','UsersController@sendEmailResetPassword');
Route::post('change-password','UsersController@updatePassword');
Route::post('check-token-expired','UsersController@checkExpiredToken');
Route::post('check-token-exist','UsersController@checkExistToken');

#Mobile Live Stream Guest
Route::prefix('v1')->group(function () {
    Route::post('/guest-online/event', 'EventsController@getWeddingEventLivestream');
});

Route::group(['middleware' => ['jwtAuth']], function () {

    Route::get('users/get-me', 'UsersController@getMe');

    /* Role Admin (STAFF ADMIN & SUPER_ADMIN)*/
    Route::group(['middleware' => 'auth.admin'], function(){
        Route::prefix('users')->group(function () {
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

        Route::resource('places', 'PlacesController');
    });

    /* Role Super Admin */
    Route::group(['middleware' => 'auth.super_admin'], function(){
        Route::prefix('users')->group(function () {
            Route::post('/super-admin/invite-admin-staff', 'UsersController@inviteNewAdminStaff');
        });
        
        /** route staffs */
        Route::get('restaurants-staffs','UsersController@getStaffAdmin');
        Route::get('staff','UsersController@getListStaff');
        Route::get('staff/{user_id}','UsersController@getStaff');
        Route::delete('staff/{user_id}','UsersController@destroyStaff');
    });

    /* Role Couple */
    Route::group(['middleware' => 'auth.couple'], function(){
        Route::prefix('/v1')->group(function () {
            Route::get('couple/event', 'EventsController@coupleDetailEvent');
            Route::get('couple/event-list', 'EventsController@coupleListGuest');
        });        
    });

    /* Role Guest */
    Route::group(['middleware' => 'auth.guest'], function(){
        Route::resource('channel','ChannelsController');
    });

    /* Role Customer (Guest + Groom + Bride) */
    Route::group(['middleware' => 'auth.customer'], function(){
        
    });
    
    Route::post('auth/logout', 'AuthController@logout');
    Route::post('admin/create','UsersController@createAdmin');
    Route::get('places-get-pre-signed', 'PlacesController@getPreSigned')->name('get.getPreSigned');
    Route::resource('restaurants','RestaurantsController');

   
});
Route::get('agora/get-token','AgoraController@generateToken')->middleware('cors');
Route::get('/send-mail', function () {
    Mail::to('anhpmt@bap.jp')->send(new SendMail()); 
    return 'A message has been sent to Mailtrap!';
});