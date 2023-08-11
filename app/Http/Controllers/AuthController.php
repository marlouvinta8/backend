<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function user() {
        return Auth::user();
    }
    public function register(Request $request) {
        return User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password'))
        ]);
    }

    public function login (Request $request) {
        if (!Auth::attempt($request->only('username', 'password'))) {
            return response()->json([
                'message' => "invalid",
                'status' => 404
            ],404);
        }

        $user = Auth::user();

        $token = $user->createToken('token')->plainTextToken;

        $cookie = cookie('jwt', $token, 60*24);

        return response()->json([
            'message' => 'Success',
        ])->withCookie($cookie);
    }

    public function logout() {
        $cookie = Cookie::forget('jwt');

        return response([
            'message' => 'Success'
        ])->withCookie($cookie);
    }
}
