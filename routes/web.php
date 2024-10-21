<?php

use Core\Routing\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::group(['as' => 'auth.'], function () {
    Route::get('login', 'AuthController@login')->name('login');
    Route::post('login', 'AuthController@authenticate')->name('authenticate');
    Route::get('register', 'AuthController@register')->name('register');
    Route::post('register', 'AuthController@store')->name('store');
    Route::get('logout', 'AuthController@logout')->name('logout');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::group(['as' => 'departments.', 'prefix' => 'departments'], function () {
        Route::get('/', 'DepartmentController@index')->name('index');
        Route::get('create', 'DepartmentController@create')->name('create');
        Route::post('store', 'DepartmentController@store')->name('store');
        Route::get('{id}', 'DepartmentController@show')->name('show');
        Route::get('{id}/edit', 'DepartmentController@edit')->name('edit');
        Route::put('{id}', 'DepartmentController@update')->name('update');
    });
});

