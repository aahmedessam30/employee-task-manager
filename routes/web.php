<?php

use Core\Routing\Route;

Route::group(['as' => 'auth.'], function () {
    Route::get('login', 'AuthController@login')->name('login');
    Route::post('login', 'AuthController@authenticate')->name('authenticate');
    Route::get('register', 'AuthController@register')->name('register');
    Route::post('register', 'AuthController@store')->name('store');
    Route::get('logout', 'AuthController@logout')->name('logout');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@dashboard')->name('dashboard');
    Route::resource('employees', 'EmployeeController');
    Route::resource('departments', 'DepartmentController');
    Route::resource('tasks', 'TaskController');
});
