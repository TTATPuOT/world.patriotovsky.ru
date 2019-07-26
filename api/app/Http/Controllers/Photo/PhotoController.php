<?php


namespace App\Http\Controllers\Photo;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Image;

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
            'image' => 'required|mimes:jpg,jpeg,png,gif'
        ]);

        if (!$validator->fails()) {

            $gallery = Gallery::where('id', $request->gallery_id) -> first();

            if ($gallery and $user->id == $gallery->user) {
                $photo = new Photo();
                $photo->gallery = $gallery->id;
                $photo->description = ($request->description) ? $request->description : '';

                $path = public_path('/storage/photos/');
                $name = time();

                $image = Image::make($request->file('image'));
                $image->encode('jpg', '100');
                $imageName = $name . '.jpg';
                $image->save($path . $imageName, '100', 'jpg');

                $thumb = Image::make($request->file('image'));
                $thumb->encode('jpg', '100');
                $thumb->fit(100);
                $thumbName = $name . '-100x100.jpg';
                $thumb->save($path . $thumbName, '100', 'jpg');

                $photo->path = $imageName;
                $photo->save();

                $result['ok'] = true;
                $result['result'] = [
                    'id' => $photo->id,
                    'gallery' => $photo->gallery,
                    'description' => $photo->description,
                    'path' => $photo->path
                ];
            } else {
                $result['message'] = 'Галерея принадлежит другому пользователю или не существует';
            }
        } else{
            $result['message'] = 'Ошибка валидации данных';
        }

        return $result;
    }
}
