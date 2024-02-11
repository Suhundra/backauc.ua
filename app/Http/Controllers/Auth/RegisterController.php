<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class RegisterController extends Controller
{
    public function registerForm(Request $request)
    {   
        try{
            $validatedData = $request->validate([
                'login' => 'required|string|min:5|unique:users',
                'password' => 'required|string|min:8',
            ]);
        }catch (ValidationException $e) {
            return response()->json([
                'error' => $e->errors(),
                'success' => false,
            ], 422);
        }

        $user = User::create([
            'login' => $validatedData['login'],
            'password' => bcrypt($validatedData['password']),
        ]);
        

        if ($user) {
            /*$token = $user->createToken('MyAppToken')->accessToken;
            $cookie = cookie('access_token', $token, 60);*/

            $userId = $user->id;

            $encryptedUserId = encrypt($userId);
            
            $cookie = cookie('access_token', $encryptedUserId, 60)
            ->withSecure(true)
            ->withSameSite('none');

            return response()->json([
                'msg' => 'The user is successfully registered!', 
                'success' => true
            ], 200)->withCookie($cookie);
        } else {
            return response()->json([
                'error' => 'Error saving data (server error).', 
                'success' => false
            ], 400);
        }
    }
}
