<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signup(SignupRequest $request)
    {
        /** @var \App\Model\User $user */
        $data = $request->validated();
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        // return response->compact('user, 'token');
        // ! 위 처럼 사용할수있지만 아래처럼 사용하는게 더 좋은거 같다
        return response()->json([
            'ok' => true,
            'error' => null,
        ]);

    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated(); // ! validate vs validated
        $ok = Auth::attempt($credentials); // ! attempt는 정확하게 어떻게 동작하는거지?
        if (!$ok) {
            return response([
                'ok' => $ok,
                'error' => 'wrongInfo',
                'message' => '이메일 또는 비밀번호가 틀렸습니다.',
            ]);
        }

        /**
         * @var \App\Models\User $user
         */
        // $check = $user->tokens()->first();

        $user = Auth::user(); // ! 왜 password는 빠져있는거지? 어떻게 동작하는거지?
        $token = $user->createToken('login')->plainTextToken;
        return response()->json([
            'ok' => $ok,
            'error' => null,
            'user' => $user,
            'token' => $token,
        ]);

    }
}