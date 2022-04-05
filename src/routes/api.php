<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user/sign-up', 'User\Auth\RegisterController@register')->name('user.sign-up');
Route::post('/user/login', 'User\Auth\LoginController@login')->name('user.login');
Route::post('/user/logout', 'User\Auth\LoginController@logout')->name('user.logout');

Route::post('/company/sign-up', 'Company\Auth\RegisterController@register')->name('company.sign-up');
Route::post('/company/login', 'Company\Auth\LoginController@login')->name('company.login');
Route::post('/company/logout', 'Company\Auth\LoginController@logout')->name('company.logout');

Route::get('/company/{id}', 'Company\CompanyController@get')->name('company.get');
Route::put('/company/{id}', 'Company\CompanyController@update')->name('company.update');


