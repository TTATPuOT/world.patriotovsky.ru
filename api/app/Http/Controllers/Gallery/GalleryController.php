<?php

namespace App\Http\Controllers\Gallery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Gallery;

class GalleryController extends Controller
{
    public function createGallery(Request $request) {
        $result = [
            'ok' => false
        ];

        // if (Auth::check()) {
        //     $user = Auth::user();
        // } else {
        //     $result['message'] = 'Ошибка аутентификации';
        //     return $result;
        // }

        $user = User::where('remember_token', $request->token) -> first();

        if (!$user or $request->token === null) {
            $result['message'] = 'Ошибка аутентификации';
            return $result;
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:155',
            'lat' => 'required',
            'lng' => 'required'
        ]);

        if (!$validator->fails()) {

            $gallery = new Gallery();
            $gallery->name = $request->name;
            $gallery->user = $user->id;
            $gallery->lat = $request->lat;
            $gallery->lng = $request->lng;
            $gallery->save();

            $result['ok'] = true;
        } else{
            $result['message'] = 'Ошибка валидации данных';
        }

        return $result;
    }
}