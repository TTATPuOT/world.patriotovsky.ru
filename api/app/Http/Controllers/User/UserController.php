<?php


namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Gallery;
use App\Photo;

class UserController extends Controller
{
    public function get(Request $request) {
        $result = [
            'ok' => false
        ];

        $user = User::where('remember_token', $request->token) -> first();

        if (!$user or $request->token === null) {
            $result['message'] = 'Ошибка аутентификации';
            return $result;
        }

        $result['result'] = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'galleries' => 0,
            'photos' => 0
        ];

        foreach (Gallery::where('user', $user->id)->cursor() as $gallery) {
            foreach (Photo::where('gallery', $gallery->id)->cursor() as $photo) {
                $result['result']['photos'] += 1;
            }
            $result['result']['galleries'] += 1;
        }

        $result['ok'] = true;

        return $result;
    }
}
