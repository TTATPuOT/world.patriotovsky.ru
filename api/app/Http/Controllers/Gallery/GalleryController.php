<?php

namespace App\Http\Controllers\Gallery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Gallery;
use App\Photo;

class GalleryController extends Controller
{
    public function create(Request $request) {
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
            $result['result'] = [
                'id' => $gallery->id,
                'name' => $gallery->name,
                'lat' => $gallery->lat,
                'lng' => $gallery->lng
            ];
        } else{
            $result['message'] = 'Ошибка валидации данных';
        }

        return $result;
    }

    public function update(Request $request) {
        $result = [
            'ok' => false
        ];

        $user = User::where('remember_token', $request->token) -> first();

        if (!$user or $request->token === null) {
            $result['message'] = 'Ошибка аутентификации';
            return $result;
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'name' => 'max:155',
            'lat' => 'numeric',
            'lng' => 'numeric'
        ]);

        if (!$validator->fails()) {

            $gallery = Gallery::where('id', $request->id) -> first();

            if ($gallery and $user->id == $gallery->user) {
                $gallery->name = ($request->name) ? $request->name : $gallery->name;
                $gallery->lat = ($request->lat) ? $request->lat : $gallery->lat;
                $gallery->lng = ($request->lng) ? $request->lng : $gallery->lng;
                $gallery->save();

                $result['ok'] = true;
                $result['result'] = [
                    'name' => $gallery->name,
                    'lat' => $gallery->lat,
                    'lng' => $gallery->lng
                ];
            } else {
                $result['message'] = 'Галерея принадлежит другому пользователю или не существует';
            }
        } else{
            $result['message'] = 'Ошибка валидации данных';
        }

        return $result;
    }

    public function delete(Request $request) {
        $result = [
            'ok' => false
        ];

        $user = User::where('remember_token', $request->token) -> first();

        if (!$user or $request->token === null) {
            $result['message'] = 'Ошибка аутентификации';
            return $result;
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer'
        ]);

        if (!$validator->fails()) {

            $gallery = Gallery::where('id', $request->id) -> first();

            if ($gallery and $user->id == $gallery->user) {
                $gallery->forceDelete();

                $result['ok'] = true;
            } else {
                $result['message'] = 'Галерея принадлежит другому пользователю или не существует';
            }
        } else{
            $result['message'] = 'Ошибка валидации данных';
        }

        return $result;
    }

    public function get(Request $request) {
        $result = [
            'ok' => false
        ];

        $user = User::where('remember_token', $request->token) -> first();

        if (!$user or $request->token === null) {
            $result['message'] = 'Ошибка аутентификации';
            return $result;
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer'
        ]);

        if (!$validator->fails()) {

            $gallery = Gallery::where('id', $request->id) -> first();

            if ($gallery and $user->id == $gallery->user) {
                foreach(Photo::where('gallery', $gallery->id)->cursor() as $photo) {
                    $result['result'][] = [
                        'id' => $photo->id,
                        'description' => $photo->description,
                        'path' => $photo->path
                    ];
                }

                $result['ok'] = true;
            } else {
                $result['message'] = 'Галерея принадлежит другому пользователю или не существует';
            }
        } else{
            $result['message'] = 'Ошибка валидации данных';
        }

        return $result;
    }

    public function getAll(Request $request) {
        $result = [
            'ok' => false
        ];

        $user = User::where('remember_token', $request->token) -> first();

        if (!$user or $request->token === null) {
            $result['message'] = 'Ошибка аутентификации';
            return $result;
        }

        foreach(Gallery::where('user', $user->id)->cursor() as $gallery) {
            $array = [
                'id' => $gallery->id,
                'name' => $gallery->name,
                'lat' => $gallery->lat,
                'lng' => $gallery->lng,
                'photo' => null
            ];;

            $photo = Photo::where('gallery', $gallery->id)->first();
            if (!empty($photo)) {
                $array['photo'] = [
                    'id' => $photo->id,
                    'description' => $photo->description,
                    'path' => $photo->path
                ];
            }

            $result['result'][] = $array;
        }

        $result['ok'] = true;

        return $result;
    }
}
