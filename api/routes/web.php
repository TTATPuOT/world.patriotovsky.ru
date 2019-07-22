<?php

Route::get('/', function () {
    return view('welcome');
});

Route::post('/gallery/create', 'Gallery\GalleryController@createGallery');

Route::post('register', 'Auth\CustomAuthController@register');

Route::post('login', 'Auth\CustomAuthController@login');

Route::post('logout', 'Auth\CustomAuthController@logout');

// Route::get('/home', 'HomeController@index')->name('home');
