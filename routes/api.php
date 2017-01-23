<?php


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

Route::group([], function () {

    // sms
    Route::group([
        'prefix' => 'sms',
    ], function () {
        Route::post('send-verify-code', 'SmsController@sendVerifyCode');
    });

    // account
    Route::group([
        'prefix' => 'account',
    ], function () {
        Route::get('validate-invite-code', 'AccountController@validateInviteCode');
        Route::post('register', 'AccountController@register');
        Route::post('login', 'AccountController@login');
        Route::post('logout', 'AccountController@logout');
        Route::get('profile', 'AccountController@getProfile');
        Route::put('profile', 'AccountController@updateProfile');
        Route::put('configs', 'AccountController@configs');
        Route::put('password-reset', 'AccountController@passwordReset');
        Route::put('password-modify', 'AccountController@passwordModify');
    });
});
