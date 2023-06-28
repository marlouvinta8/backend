<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(Request $request){
       return User::create([
            'fullname' => $request->input('fullname'),
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password'))
        ]);
    }

    public function login(Request $request){
        if(!Auth::attempt($request->only('username', 'password'))){
            return response([
                'message' => 'Invalid Credentials',
                'status' => 401
            ],401);
        }
        $user = Auth::user();

        $token = $user->createToken('token')->plainTextToken;

        return $token;
    }
    public function user() {
        return 'Authenticated user';
    }
}