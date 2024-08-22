<?php

use Illuminate\Support\Facades\Route;

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

Route::prefix('admin')->group(function () {
    Route::get('/', 'AdminController@index')->middleware('guest')->name('admin.index');
    Route::post('/login', 'AdminController@login')->middleware('guest')->name('admin.login');
    Route::post('/logout', 'AdminController@logout')->middleware('auth')->name('admin.logout');
    Route::get('/dashboard', 'AdminController@dashboard')->middleware('auth')->name('admin.dashboard');

    Route::prefix('user')->middleware('auth')->group(function () {
        Route::get('/', 'UserController@index')->name('admin.user.index');
        Route::get('/create', 'UserController@create')->name('admin.user.create');
        Route::post('/store', 'UserController@store')->name('admin.user.store');
        Route::get('/edit/{id}', 'UserController@edit')->name('admin.user.edit');
        Route::post('/update/{id}', 'UserController@update')->name('admin.user.update');
        Route::delete('/delete/{id}', 'UserController@destroy')->name('admin.user.delete');
    });
    
    Route::prefix('password')->group(function () {
        Route::get('/forgot', 'PasswordResetController@forgotPassword')->name('password.forgot');
        Route::get('/reset/{token}', 'PasswordResetController@showResetForm')->name('password.reset');
        Route::post('/reset/{token}', 'PasswordResetController@reset')->name('password.reset.save');
        Route::post('/email', 'PasswordResetController@create')->name('password.email');
    });
});
