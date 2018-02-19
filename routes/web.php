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
    return view('home');
})->name('home');

Auth::routes();

Route::resource('users', 'UserController', ['except' => ['create', 'store', 'show']]);

Route::resource('taskstatuses', 'TaskStatusController', ['except' => ['show']]);
