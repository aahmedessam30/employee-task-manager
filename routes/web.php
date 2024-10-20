<?php

use Core\Routing\Route;

Route::get('/', function () {
    return 'Hello from Web';
})->name('home');

Route::group(['prefix' => 'users'], function () {
    Route::get('/', 'UserController@index')->name('users.index');
    Route::get('/{id}', 'UserController@show')->name('users.show');
});
