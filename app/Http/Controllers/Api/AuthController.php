<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signup(SignupRequest $request)
    {
        /** @var \App\Model\User $user */
        $data = $request->validate();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        $token = $user->createToken('main')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);

        // return response->compact('user, 'token');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated(); // ! validate vs validated
        $ok = Auth::attempt($credentials);
        if (!$ok) {
            return response([
                'ok' => $ok,
                'error' => 'wrongInfo',
                'message' => 'Provided email address or password is incorrect',
            ]);
        }

        /**
         * @var \App\Models\User $user
         */

        $user = Auth::user();
        $token = $user->createToken('main')->plainTextToken;
        return response()->json([
            'ok' => $ok,
            'error' => null,
            'user' => $user,
            'token' => $token,
        ]);

    }

    public function logout(Request $request)
    {
        /**
         * @var \App\Models\User $user
         */
        $request->user()->currentAccessToken()->delete();
        return response(compact('', 204));

    }

}