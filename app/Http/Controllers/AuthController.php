<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;


class AuthController extends Controller
{
    public function register(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'required|string|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);

        $user = User::create($validatedData);
        
        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user'=> $user, 'accessToken' => $accessToken]);
    }

    public function login(Request $request) {
        $login = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if(!Auth::attempt($login)) {
            return response('Invalid credidentials', 401);
        }

        $accessToken = Auth::user()->createToken('authToken')->accessToken;

        return response(['user' => Auth::user(), 'access_token' => $accessToken]);
    }
}
