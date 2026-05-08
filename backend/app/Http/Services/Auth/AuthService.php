<?php

namespace App\Http\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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

        return $this->generateResponse($user);
    }

    /**
     * Authenticate user and generate token.
     */
    public function login(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Kredensial yang diberikan salah.'],
            ]);
        }

        return $this->generateResponse($user);
    }

    /**
     * Revoke current user token.
     */
    public function logout($user)
    {
        return $user->currentAccessToken()->delete();
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