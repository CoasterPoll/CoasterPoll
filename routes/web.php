<?php

use \Illuminate\Support\Facades\Route;

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

Route::get('/', function (\Illuminate\Http\Request $request) {
    return view('home');
})->name('home');

Auth::routes();

Route::group(['middleware' => ['role:Admin', 'auth'], 'prefix' => 'console'], function() {
    Route::get('/', 'AdminController@dashboard')->name('admin');
    Route::get('/search', 'AdminController@search')->name('admin.search');

    Route::group(['middleware' => ['can:Can manage users']], function() {
        Route::get('user/{id}', 'Users\UserController@getUser')->name('admin.user')->where('id', '[0-9]+');
        Route::post('user/update', 'Users\UserController@postUser')->name('admin.user.post');
        Route::delete('user/role/remove', 'Users\PermissionsController@deleteUserRole')->name('admin.user.role.delete');
        Route::post('user/role/grant', 'Users\PermissionsController@postUserRole')->name('admin.user.role.post');
        Route::post('user/permission/grant', 'Users\PermissionsController@postUserPermission')->name('admin.user.permission.post');
    });

    Route::group(['middleware' => ['can:Can manage roles']], function() {
        Route::get('user/roles/{id?}', 'Users\PermissionsController@getRoles')->name('admin.user.roles')->where('id', '[0-9]+');
        Route::post('user/roles', 'Users\PermissionsController@postRole')->name('admin.user.roles.post');
        Route::delete('user/roles', 'Users\PermissionsController@deleteRole')->name('admin.user.roles.delete');
    });
});