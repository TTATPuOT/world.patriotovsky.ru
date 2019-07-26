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
Route::post('/gallery/get', 'Gallery\GalleryController@get');
Route::post('/gallery/getAll', 'Gallery\GalleryController@getAll');

Route::post('/photo/create', 'Photo\PhotoController@create')->middleware('optimizeImages');

Route::post('/user/info', 'User\UserController@info');

Route::post('check', 'Auth\CustomAuthController@check');

// Route::get('/home', 'HomeController@index')->name('home');
