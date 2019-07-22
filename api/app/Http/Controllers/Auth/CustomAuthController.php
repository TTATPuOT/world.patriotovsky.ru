<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\User;

class CustomAuthController extends Controller
{
    public function register(Request $request) {
        $result = [
            'ok' => false
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:155',
            'email' => 'required|unique:users|email',
            'password' => 'required|confirmed|min:6'
        ]);

        if (!$validator->fails()) {

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            $result['ok'] = true;
            $result['result'] = [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ];
        } else{
            $result['message'] = 'Ошибка валидации данных';
        }

        return $result;
    }

    public function login(Request $request) {
        $result = [
            'ok' => false
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!$validator->fails()) {

            $user = User::where('email', $request->email) -> first();

            if ($user) {
                //Auth::login($user, true);
                $user->remember_token = Hash::make($request->password . now());
                $user->save();

                $result['ok'] = true;
                $result['result'] = [
                    'token' => $user->remember_token
                ];
            } else {
                $result['message'] = 'Пользователя с таким email не существует';
            }
        } else{
            $result['message'] = 'Ошибка валидации данных';
        }

        return $result;
    }

    public function logout(Request $request) {
        $result = [
            'ok' => false
        ];

        $user = User::where('remember_token', $request->token) -> first();

        // if (Auth::check())
        if ($user) {
            //Auth::logout();
            $user->remember_token = null;
            $user->save();

            $result['ok'] = true;
        } else {
            $result['message'] = 'Пользователь не авторизован';
            return $result;
        }

        return $result;
    }

    public function check(Request $request) {
        $result = [
            'ok' => false
        ];

        $user = User::where('remember_token', $request->token) -> first();

        if (!$user or $request->token === null) {
            $result['message'] = 'Ошибка аутентификации';
            return $result;
        }

        $result['ok'] = true;
        $result['data'] = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ];

        return $result;
    }
}