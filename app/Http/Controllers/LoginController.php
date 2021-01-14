<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'min:6', 'max:255', 'exists:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if (Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ])) {
            $token = Str::random(80);
            $api_token = hash('sha256', $token);

            $user = Auth::user();
            $user->update(['api_token' => $api_token]);

            return response()->json([
                'token' => $token
            ]);
        }

        return response()->json([
            'message' => 'Incorrect login details.'
        ]);
    }
}
