<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];
        $validator = Validator::make(
            $credentials,
            [
                'email' => ['required', 'email'],
                'password' => ['required']
            ]
        );
        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }
        if (!Auth::attempt($credentials)) {
            return response(['failedMessage' => 'Credentials Invalid'], 401);
        }
        $user = User::where('email', $request->input('email'))->first();
        $token = $user->createToken('My Token')->plainTextToken;
        return response()->json([
            'successMessage' => 'Login Success',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json(['successMessage' => 'Logout Success'], 200);
    }
}
