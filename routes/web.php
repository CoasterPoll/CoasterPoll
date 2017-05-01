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
Route::get('/home', 'HomeController@index');

Route::post('/analytics/view', 'AnalyticsController@view')->name('analytics.view');

Route::get('contact', 'ContactController@general')->name('contact');
Route::get('contact/general', 'ContactController@general');
Route::get('contact/coaster/{coaster?}', 'ContactController@coaster')->name('contact.coaster');
Route::post('contact', 'ContactController@post')->name('contact.post');

// ## Authentication
\Illuminate\Support\Facades\Auth::routes();
Route::get('login/{service}', 'Auth\SocialLoginController@redirect')->name('auth.social');
Route::get('login/{service}/callback', 'Auth\SocialLoginController@callback')->name('auth.social.callback');

Route::group(['prefix' => 'user', 'middleware' => 'auth'], function() {
    Route::get('/settings', 'Users\PreferencesController@settings')->name('user.settings');
    Route::post('/settings', 'Users\PreferencesController@updateSettings')->name('user.settings.post');

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
        Route::get('ridden', 'Coasters\MainController@ridden')->name('coasters.ridden')->middleware('can:Can track coasters');
        Route::get('rank', 'Coasters\MainController@rank')->name('coasters.rank')->middleware('can:Can rank coasters');
        Route::post('rank/update', 'Coasters\MainController@updateRank')->name('coasters.rank.post')->middleware('can:Can rank coasters');
        Route::put('rank/new', 'Coasters\MainController@newRank')->name('coasters.rank.put')->middleware('can:Can rank coasters');

        Route::post('/track/ridden', 'Coasters\MainController@ride')->name('coasters.track.ride')->middleware('can:Can track coasters');

        Route::post('/p/update', 'Coasters\ParkController@update')->name('coasters.park.update')->middleware('can:Can manage coasters');
        Route::post('/c/update', 'Coasters\CoasterController@update')->name('coasters.coaster.update')->middleware('can:Can manage coasters');
        Route::post('/m/update', 'Coasters\ManufacturerController@update')->name('coasters.manufacturer.update')->middleware('can:Can manage coasters');

        Route::get('/new/park', 'Coasters\ParkController@new')->name('coasters.park.new')->middleware('can:Can manage coasters');
        Route::get('/new/coaster', 'Coasters\CoasterController@new')->name('coasters.coaster.new')->middleware('can:Can manage coasters');
        Route::get('/new/manufacturer', 'Coasters\ManufacturerController@new')->name('coasters.manufacturer.new')->middleware('can:Can manage coasters');

        Route::group(['middleware' => 'can:Can run results'], function() {
            Route::get('/results/manage/{page?}', 'Coasters\ResultsController@manage')->name('coasters.results.manage');
            Route::post('/results/run', 'Coasters\ResultsController@run')->name('coasters.results.run');
            Route::post('/results/page', 'Coasters\ResultsController@savePage')->name('coasters.results.page.post');
            Route::delete('/results/page', 'Coasters\ResultsController@deletePage')->name('coasters.results.page.delete');
            Route::delete('/results/group', 'Coasters\ResultsController@deleteGroup')->name('coasters.results.group.delete');
        });
    });

    Route::get('/results/{url?}', 'Coasters\ResultsController@results')->name('coasters.results');

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
    Route::get('/contact/{id?}','ContactController@admin')->name('admin.contact');

    Route::group(['middleware' => ['can:Can manage users']], function() {
        Route::get('user/{id}', 'Users\UserController@getUser')->name('admin.user')->where('id', '[0-9]+');
        Route::post('user/update', 'Users\UserController@postUser')->name('admin.user.post');
        Route::delete('user/role/remove', 'Users\PermissionsController@deleteUserRole')->name('admin.user.role.delete');
        Route::post('user/role/grant', 'Users\PermissionsController@postUserRole')->name('admin.user.role.post');
        Route::post('user/permission/grant', 'Users\PermissionsController@postUserPermission')->name('admin.user.permission.post');
        Route::post('user/lock', 'Users\UserController@lockAccount')->name('admin.user.lock.post');
        Route::post('user/unlock', 'Users\UserController@unlockAccount')->name('admin.user.unlock.post');
        Route::post('user/reset', 'Users\UserController@resetPassword')->name('admin.user.reset-password');
    });

    Route::group(['middleware' => ['can:Can manage roles']], function() {
        Route::get('user/roles/{id?}', 'Users\PermissionsController@getRoles')->name('admin.user.roles')->where('id', '[0-9]+');
        Route::post('user/roles', 'Users\PermissionsController@postRole')->name('admin.user.roles.post');
        Route::delete('user/roles', 'Users\PermissionsController@deleteRole')->name('admin.user.roles.delete');
    });

    Route::group(['middleware' => ['can:Can write content'], 'prefix' => 'content'], function() {
        Route::get('pages', 'ContentController@adminPages')->name('admin.content.pages');
        Route::post('page', 'ContentController@savePage')->name('admin.content.page.post');
        Route::get('page/{page?}', 'ContentController@adminPage')->name('admin.content.page');

        Route::get('links/{link?}', 'ContentController@adminLinks')->name('admin.content.links');
        Route::post('links', 'ContentController@saveLink')->name('admin.content.link.post');
        Route::delete('link', 'ContentController@deleteLink')->name('admin.content.link.delete');
    });
});

// ## CMS?
Route::get('{page}', 'ContentController@page')->name('content');