<?php

Route::get('/', function () {
    return view('welcome');
});

Route::post('register', 'Auth\CustomAuthController@register');
Route::post('login', 'Auth\CustomAuthController@login');
Route::post('logout', 'Auth\CustomAuthController@logout');

Route::post('/gallery/create', 'Gallery\GalleryController@create');
Route::post('/gallery/update', 'Gallery\GalleryController@update');
Route::post('/gallery/delete', 'Gallery\GalleryController@delete');

Route::post('/photo/create', 'Photo\PhotoController@create');

Route::post('check', 'Auth\CustomAuthController@check');

// Route::get('/home', 'HomeController@index')->name('home');
