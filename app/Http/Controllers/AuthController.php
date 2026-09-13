<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\ApiResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        return ApiResponse::success(
            array_merge(['user' => $this->userPayload($user)], $this->issueTokens($user)),
            'Register successful',
            201
        );
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return ApiResponse::error('Invalid email or password', 401);
        }

        return ApiResponse::success(
            array_merge(['user' => $this->userPayload($user)], $this->issueTokens($user)),
            'Login successful'
        );
    }

    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();

        $request->user()->currentAccessToken()->delete();

        return ApiResponse::success($this->issueTokens($user), 'Token refreshed');
    }

    private function issueTokens(User $user): array
    {
        return [
            'access_token'  => $user->createToken('access', ['*'], now()->addMinutes(15))->plainTextToken,
            'refresh_token' => $user->createToken('refresh', ['auth:refresh'], now()->addDays(30))->plainTextToken,
            'token_type'    => 'Bearer',
        ];
    }

    private function userPayload(User $user): array
    {
        return [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role,
        ];
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return ApiResponse::success(null, 'Logged out');
    }

    public function me(Request $request): JsonResponse
    {
        return ApiResponse::success($request->user(), 'Current user');
    }
}
