<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Admin\App\Http\Controllers\EmailController;
use Modules\Frontend\App\Http\Controllers\ApiController;

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

Route::get('/categories', 'ApiController@getCategories')->name('get.categories');

Route::get('/category/{slug}', 'ApiController@getCategory')->name('get.category');

Route::get('/category/albums/{slug}', 'ApiController@getCategoryAlbums')->name('get.category.albums');

Route::post('/contact', [EmailController::class, 'store']);

Route::get('/album/{slug}', 'ApiController@getAlbum')->name('get.album');

Route::get('/album/{slug}/images', 'ApiController@getAlbumImages')->name('get.album.images');

Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
    Route::get('frontend', fn (Request $request) => $request->user())->name('frontend');
});
