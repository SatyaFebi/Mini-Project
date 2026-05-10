<?php

namespace App\Http\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Register a new user and generate token.
     */
    public function register(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::guard('web')->login($user);
        if (request()->hasSession()) {
            request()->session()->regenerate();
        }

        return $this->generateResponse($user);
    }

    /**
     * Authenticate user and generate token.
     */
    public function login(array $data)
    {
        if (!Auth::guard('web')->attempt($data)) {
            throw ValidationException::withMessages([
                'email' => ['Kredensial yang diberikan salah.'],
            ]);
        }

        $user = Auth::guard('web')->user();
        if (request()->hasSession()) {
            request()->session()->regenerate();
        }

        return $this->generateResponse($user);
    }

    /**
     * Revoke current user token.
     */
    public function logout($user)
    {
        if ($user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        Auth::guard('web')->logout();
        
        if (request()->hasSession()) {
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }
    }

    /**
     * Format the response data.
     */
    private function generateResponse(User $user)
    {
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}