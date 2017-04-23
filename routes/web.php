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

Route::get('/', 'HomeController@index')->name('home');

Route::post('/analytics/view', 'AnalyticsController@view')->name('analytics.view');

// ## Authentication
\Illuminate\Support\Facades\Auth::routes();
Route::group(['prefix' => 'user', 'middleware' => 'auth'], function() {
    Route::get('/demographics', 'Users\PreferencesController@demographics')->name('user.demographics');
    Route::post('/demographics', 'Users\PreferencesController@saveDemographics')->name('user.demographics.post');

    Route::get('/notifications', 'NotificationController@all')->name('notifications');
    Route::post('/notifications/mark', 'NotificationController@mark')->name('notifications.mark');
    Route::delete('/notifications/delete', 'NotificationController@delete')->name('notifications.delete');
});

// ## Coasters
Route::group(['middleware' => 'ChaseH\Http\Middleware\RiddenCoastersMiddleware'], function() {
    Route::get('search', 'Coasters\MainController@search')->name('coasters.search');
    Route::get('list', 'Coasters\MainController@display')->name('coasters.coasters');

    Route::group(['middleware' => 'auth'], function() {
        Route::get('ridden', 'Coasters\MainController@ridden')->name('coasters.ridden');
        Route::get('rank', 'Coasters\MainController@rank')->name('coasters.rank');
        Route::post('rank/update', 'Coasters\MainController@updateRank')->name('coasters.rank.post');
        Route::put('rank/new', 'Coasters\MainController@newRank')->name('coasters.rank.put');

        Route::post('/track/ridden', 'Coasters\MainController@ride')->name('coasters.track.ride');

        Route::post('/p/update', 'Coasters\ParkController@update')->name('coasters.park.update');
        Route::post('/c/update', 'Coasters\CoasterController@update')->name('coasters.coaster.update');
        Route::post('/m/update', 'Coasters\ManufacturerController@update')->name('coasters.manufacturer.update');

        Route::get('/new/park', 'Coasters\ParkController@new')->name('coasters.park.new');
        Route::get('/new/coaster', 'Coasters\CoasterController@new')->name('coasters.coaster.new');
        Route::get('/new/manufacturer', 'Coasters\ManufacturerController@new')->name('coasters.manufacturer.new');
    });

    Route::get('P{park}/{tab?}', 'Coasters\ParkController@short')->name('coasters.park.id')->where(['park' => '[0-9]+']);
    Route::get('C{coaster}/{tab?}', 'Coasters\CoasterController@short')->name('coasters.coaster.id');
    Route::get('M{manufacturer}/{tab?}', 'Coasters\ManufacturerController@short')->name('coasters.manufacturer.id');

    /**
     * Before changing these, you ALSO must change the links in search.blade.php and _scripts.blade.php
     */
    Route::get('/m/{manufacturer}', 'Coasters\ManufacturerController@view')->name('coasters.manufacturer');
    // Leave me last!
    Route::get('/p/{park}', 'Coasters\ParkController@view')->name('coasters.park');
    Route::get('/p/{park}/{coaster}', 'Coasters\CoasterController@view')->name('coasters.coaster');
});



// ## Admin
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