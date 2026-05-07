<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Http\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * Handle user registration
     */
    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register($request->validated());

        return (new AuthResource($result))
            ->additional(['message' => 'Registrasi berhasil']);
    }

    /**
     * Handle user login
     */
    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->validated());

        return (new AuthResource($result))
            ->additional(['message' => 'Login berhasil']);
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'Berhasil keluar'
        ]);
    }

    /**
     * Get authenticated user profile
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
