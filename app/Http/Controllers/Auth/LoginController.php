<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;

class LoginController extends Controller
{
    
    private $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }
    public function loginForm(Request $request)
    {
        try{
            $validatedData = $request->validate([
                'login' => 'required|string|min:5',
                'password' => 'required|string|min:8',
            ]);
        }catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
                'success' => false,
            ], 422);
        }

        $credentials = [
            'login' => $validatedData['login'],
            'password' => $validatedData['password'],
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $userId = $user->id;
        
            $encryptedUserId = encrypt($userId);
            
            $cookie = cookie('access_token', $encryptedUserId, 60)
            ->withSecure(true)
            ->withSameSite('none');

            return response()->json([
                'msg' => 'Authentication successful', 
                'success' => true
            ], 200)->withCookie($cookie);
        } else {
            return response()->json([
                'error' => 'Unauthorized '. __('auth.failed'),
                'success' => false
            ], 401);
        }

    }
}
