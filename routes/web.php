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
Auth::routes();

Route::get('/', 'Auth\LoginController@showLogin');
Route::get('login', 'Auth\LoginController@showLogin')->name('login');
Route::post('login', 'Auth\LoginController@doLogin')->name('login');

Route::group(['middleware' => ['auth:web']], function () {
    //AUTH
    Route::post('logout', 'Auth\LoginController@doLogout');
    Route::get('setpassword/{users}', 'UsersController@showSetpassword');
    Route::post('setpassword/{users}', 'UsersController@setpassword');
    Route::get('resetpassword/{users}', 'UsersController@resetpassword');

    //DASHBOARD
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/home', 'HomeController@index')->name('home');

    //ROLE AND PERMISSION
    Route::resource('roles','RoleController');

    //USERS
    Route::get('/users', 'UsersController@index');
    Route::get('/users/create', 'UsersController@create');
    Route::post('/users', 'UsersController@store');
    Route::get('/users/{id}', 'UsersController@edit');
    Route::put('/users/{users}', 'UsersController@update');
    Route::get('/users/{users}/deactivate', 'UsersController@deactivate');
    Route::get('/users/{users}/reactivate', 'UsersController@reactivate');
});

Route::group(['middleware' => ['auth:web']], function () {
    Route::get('/project', 'ProjectController@index');
    Route::get('/project/create', 'ProjectController@create');
    // Route::get('/project/detail', 'ProjectController@show'); // temp
    Route::post('/project', 'ProjectController@store');
    Route::get('/project/{id}', 'ProjectController@edit');
    Route::put('/project/{project}', 'ProjectController@update');
    Route::get('/project/{project}/detail', 'ProjectController@show');
    Route::post('/project/{project}/submit_progress', 'ProjectController@submit_progress');
    Route::get('/project/{project}/deactivate', 'ProjectController@deactivate');
    Route::get('/project/{project}/reactivate', 'ProjectController@reactivate');
    Route::get('/project/{project}/change_status/{status}', 'ProjectController@change_status');


    Route::get('/owner', 'OwnerController@index');
    Route::get('/owner/create', 'OwnerController@create');
    Route::post('/owner', 'OwnerController@store');
    Route::get('/owner/{id}', 'OwnerController@edit');
    Route::put('/owner/{owner}', 'OwnerController@update');
    Route::get('/owner/{owner}/deactivate', 'OwnerController@deactivate');
    Route::get('/owner/{owner}/reactivate', 'OwnerController@reactivate');
});
