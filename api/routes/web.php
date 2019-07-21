<?php

use Illuminate\Http\Request;
use App\Gallery;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/gallery/create', function (Request $request) {
    $result = [
        'ok' => false
    ];

    $validator = Validator::make($request->all(), [
        'name' => 'required|max:155',
        'user' => 'required|integer',
        'lat' => 'required',
        'lng' => 'required',
    ]);

    if (!$validator->fails()) {

        $gallery = new Gallery();
        $gallery->name = $request->name;
        $gallery->user = $request->user;
        $gallery->lat = $request->lat;
        $gallery->lng = $request->lng;
        $gallery->save();

        $result['ok'] = true;
    } else{
        $request['message'] = 'Ошибка валидации данных';
    }

    return $result;
});