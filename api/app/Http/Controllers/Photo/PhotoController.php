<?php


namespace App\Http\Controllers\Photo;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use App\User;
use App\Gallery;
use App\Photo;

class PhotoController extends Controller
{
    public function create(Request $request) {
        $result = [
            'ok' => false
        ];

        $user = User::where('remember_token', $request->token) -> first();

        if (!$user or $request->token === null) {
            $result['message'] = 'Ошибка аутентификации';
            return $result;
        }

        $validator = Validator::make($request->all(), [
            'gallery_id' => 'required|integer',
            'description' => 'max:535',
            'image' => 'required|mimes:jpeg|max:4096'
        ]);

        if (!$validator->fails()) {

            $gallery = Gallery::where('id', $request->gallery_id) -> first();

            if ($gallery and $user->id == $gallery->user) {
                $photo = new Photo();
                $photo->gallery = $gallery->id;
                $photo->description = ($request->description) ? $request->description : '';
                $photo->path = Storage::disk('public')->put('photos', $request->image);
                $photo->save();

                $result['ok'] = true;
                $result['result'] = [
                    'id' => $photo->id,
                    'gallery' => $photo->gallery,
                    'description' => $photo->description,
                    'image_name' => $photo->path
                ];
            } else {
                $result['message'] = 'Галерея принадлежит другому пользователю';
            }
        } else{
            $result['message'] = 'Ошибка валидации данных';
        }

        return $result;
    }
}