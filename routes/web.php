<?php

Route::get('', function () {
    return redirect()->route('home');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {

    Route::get('home', 'HomeController@index')->name('home');

    Route::get('profile', 'ProfileController@edit')->name('profile.edit');
    Route::post('profile', 'ProfileController@update')->name('profile.update');
});