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

Route::prefix('v1')->group(function () {

    Route::post('/auth/register', 'AuthController@register');
    Route::post('/auth/login', 'AuthController@login');
    Route::post('/auth/customer/login', 'AuthCustomerController@login');
    Route::post('/auth/customer/token-login', 'AuthCustomerController@tokenLogin');
    Route::post('/auth/table-account/login', 'AuthTableAccountController@login');
    #API CHANGE PASSWORD
    Route::post('/forgot-password','UsersController@sendEmailResetPassword');
    Route::post('/change-password','UsersController@updatePassword');
    Route::post('/check-token-expired','UsersController@checkExpiredToken');
    Route::post('/check-token-exist','UsersController@checkExistToken');

    #Mobile Live Stream Guest
    Route::post('/guest-online/event', 'EventsController@getWeddingEventLivestream');

    Route::group(['middleware' => ['jwtAuth']], function () {

        Route::get('/users/get-me', 'UsersController@getMe');

        /* Role Admin (STAFF ADMIN & SUPER_ADMIN)*/
        Route::group(['middleware' => 'auth.admin'], function(){
            Route::prefix('/users')->group(function () {
                Route::put('/password-verify/update', 'UsersController@updatePasswordWithVerify');
            });
        });
        
        /* Role Staff Admin */
        Route::group(['middleware' => 'auth.admin_staff'], function(){
            Route::resource('/event','EventsController');
            Route::resource('/time-table', 'WeddingTimeTableController');
            Route::prefix('/users')->group(function () {
                Route::put('/staff-admin/create-or-update', 'UsersController@upadateStaffAdmin');
            });
            
            Route::prefix('/staff')->group(function () {
                Route::get('/guest-participant/get', 'CustomersController@staffGetGuestInfo');
                Route::get('/wedding-card/detail', 'WeddingCardsController@staffGetWeddingCard');
                Route::get('/guest-list', 'CustomersController@staffListGuest');
                Route::post('/guest-participant/update', 'CustomersController@staffUpdateGuestInfo');
                Route::post('/guest-participant/reoder-row', 'CustomersController@staffReoderGuest');
            });

            Route::resource('/places', 'PlacesController');
        });

        /* Role Super Admin */
        Route::group(['middleware' => 'auth.super_admin'], function(){
            Route::prefix('/users')->group(function () {
                Route::post('/super-admin/invite-admin-staff', 'UsersController@inviteNewAdminStaff');
            });
            
            /** route staffs */
            Route::get('/restaurants-staffs','UsersController@getStaffAdmin');
            Route::get('/staff','UsersController@getListStaff');
            Route::get('/staff/{user_id}','UsersController@getStaff');
            Route::delete('/staff/{user_id}','UsersController@destroyStaff');
        });

        /* Role Couple */
        Route::group(['middleware' => 'auth.couple'], function(){
            Route::get('/couple/wedding-card/get-pre-signed', 'WeddingCardsController@getPreSigned');

            Route::get('/couple/event', 'EventsController@coupleDetailEvent');
            Route::get('/couple/guest-list', 'CustomersController@coupleListGuest');
            
            Route::prefix('/couple/event')->group(function () {
                Route::post('/thank-message/update', 'EventsController@updateThankMessage');
                Route::post('/participant/update', 'CustomersController@coupleUpdateGuestInfo');
                Route::post('/participant/reoder-row', 'CustomersController@coupleReoderGuest');
            });

            Route::get('/couple/wedding-card/notify-to-staff', 'WeddingCardsController@notifyToStaff');
            Route::get('/couple/event/notify-to-planner', 'EventsController@notifyToPlanner');

            Route::resource('/couple/template-card', 'TemplateCardsController');
            Route::resource('/couple/template-content', 'TemplateContentController');
            Route::resource('/couple/wedding-card', 'WeddingCardsController');
            Route::resource('/couple/bank-account', 'BankAccountsController');
            Route::resource('/couple/participant', 'CustomersController');
            Route::resource('/couple/customer-task', 'CustomerTasksController');
        });

        /* Role Customer*/
        Route::group(['middleware' => 'auth.customer'], function(){
            Route::get('/customer/event', 'EventsController@getWeddingEventWithBearerToken');
        });

        Route::put('/customer/event/state-livesteam', 'EventsController@updateStateLivesteam');
        
        Route::post('/auth/logout', 'AuthController@logout');
        Route::post('/admin/create','UsersController@createAdmin');
        Route::get('/places-get-pre-signed', 'PlacesController@getPreSigned')->name('get.getPreSigned');
        Route::resource('/restaurants','RestaurantsController');
        Route::resource('/table-positon', 'TablePositionsController');
        Route::resource('/table-account', 'TableAccountController');
        Route::resource('/customer', 'CustomersController');
        Route::get('/customer-in-wedding', 'CustomersController@getListCustomerInWedding');
        Route::resource('/channel','ChannelsController');
        Route::post('agora/store-rtm','AgoraController@storeRtm');
        Route::post('agora/store-rtc','AgoraController@storeRtc');
    });

    Route::get('/agora/get-token','AgoraController@generateToken')->middleware('cors');

    Route::get('/agora/create-channel', function()  {
        \Artisan::call('command:CreateChannel');
        
        echo true;
    });

    Route::get('/agora/update-token-channel', function()  {
        \Artisan::call('command:UpdateTokenChannel');

        echo true;
    });

    Route::get('/agora/remove-channel', function()  {
        \DB::table('channels')->delete();

        echo true;
    });

    Route::get('/dump-wedding', function(){
        \Artisan::call('db:seed --class=WeddingSeeder');
    });

    Route::get('/un-dump-wedding', function(){
        \Artisan::call('db:seed --class=UnWeddingSeeder');
    });

});