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

        Route::get('/set-locale/{locale}', function ($locale) {
        if (!in_array($locale, ['en', 'hu', 'sr'])) {
            abort(400);
        }

        session(['locale' => $locale]);

        return redirect()->back();
    })->name('admin.setLocale');

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

    Route::prefix('category')->middleware('auth')->group(function () {
        Route::get('/', 'CategoryController@index')->name('admin.category.index');
        Route::get('/create', 'CategoryController@create')->name('admin.category.create');
        Route::post('/store', 'CategoryController@store')->name('admin.category.store');
        Route::get('/edit/{id}', 'CategoryController@edit')->name('admin.category.edit');
        Route::put('/update/{id}', 'CategoryController@update')->name('admin.category.update');
        Route::delete('/delete/{id}', 'CategoryController@destroy')->name('admin.category.delete');
    });

    Route::prefix('album')->middleware('auth')->group(function () {
        Route::get('/', 'AlbumController@index')->name('admin.album.index');
        Route::get('/create', 'AlbumController@create')->name('admin.album.create');
        Route::post('/store', 'AlbumController@store')->name('admin.album.store');
        Route::get('/edit/{id}', 'AlbumController@edit')->name('admin.album.edit');
        Route::put('/update/{id}', 'AlbumController@update')->name('admin.album.update');
        Route::delete('/delete/{id}', 'AlbumController@destroy')->name('admin.album.delete');
        Route::delete('/image','AlbumController@removeImage')->name('admin.album.image.remove');
    });
});
