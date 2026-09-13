<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    // Bước 1: redirect user sang trang đăng nhập Google
    public function redirect(): JsonResponse
    {
        $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();

        return ApiResponse::success(['url' => $url], 'Redirect to Google');
    }

    // Bước 2: Google redirect về đây sau khi user chọn account
    public function callback(): JsonResponse
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            ['name'  => $googleUser->getName(), 'password' => '']
        );

        return ApiResponse::success([
            'user'         => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
            'access_token'  => $user->createToken('access', ['*'], now()->addMinutes(15))->plainTextToken,
            'refresh_token' => $user->createToken('refresh', ['auth:refresh'], now()->addDays(30))->plainTextToken,
            'token_type'    => 'Bearer',
        ], 'Google login successful');
    }
}
