<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('administrator', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('administrator', 'Auth\LoginController@login');

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
//Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

//Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//Route::post('register', 'Auth\RegisterController@register');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/web/appointment/{app_key}', 'Web\AppointmentController@index');
Route::get('/web/appointment/{app_key}/login', 'Web\AppointmentController@login');
Route::get('/web/appointment/{app_key}/profile', 'Web\AppointmentController@profile');
Route::get('/web/appointment/{app_key}/update-form', 'Web\AppointmentController@updateform');
Route::get('/web/appointment/{app_key}/list', 'Web\AppointmentController@list');
Route::get('/web/appointment/{app_key}/steps', 'Web\AppointmentController@steps');
Route::get('/web/appointment/{app_key}/terms', 'Web\AppointmentController@terms');
Route::get('/web/appointment/{app_key}/privacy-policy', 'Web\AppointmentController@privacyPolicy');


//Route::get('/administrator', function () {
//    return view('administrator');
//});
