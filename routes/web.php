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
Route::get('login', 'Controller@getLogin')->name('login');
Route::post('login', 'Controller@postLogin')->name('login');
Route::get('register', 'Controller@getRegister')->name('register');
Route::post('register', 'Controller@postRegister')->name('register');
Route::group(['middleware' => 'CheckLogin'], function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('/');
    Route::get('logout', 'Controller@getLogout')->name('logout');

    Route::get('employee', 'Controller@getEmployee')->name('employee');
    Route::post('employee', 'Controller@getEmployee')->name('employee');
    Route::post('new-employee', 'Controller@postNewEmployee')->name('new-employee');
    Route::post('edit-employee', 'Controller@postEditEmployee')->name('edit-employee');
    Route::post('delete-employee', 'Controller@postDeleteEmployee')->name('delete-employee');

    Route::get('position', 'Controller@getPosition')->name('position');
    Route::post('position', 'Controller@getPosition')->name('position');
    Route::post('new-position', 'Controller@postNewPosition')->name('new-position');
    Route::post('edit-position', 'Controller@postEditPosition')->name('edit-position');
    Route::post('delete-position', 'Controller@postDeletePosition')->name('delete-position');
});
