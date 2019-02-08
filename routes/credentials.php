<?php

/*
|--------------------------------------------------------------------------
| Credencials Routes
|--------------------------------------------------------------------------
|
| Rotas do modulo de Credenciais
|
*/

Route::get('users/all', 'UsersController@all')->name('users.all');
Route::resource('users', 'UsersController');

Route::get('profiles/all', 'ProfilesController@all')->name('profiles.all');
Route::resource('profiles', 'ProfilesController')->except(['create']);
