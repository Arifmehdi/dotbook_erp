<?php

namespace Modules\Core\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\Core\Http\Requests\Auth\LoginRequest;
use Modules\Core\Http\Response\ApiResponse;

class JwtAuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $user = User::where('username', $credentials['username'])->first();
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['Login failed! please try again with correct credentials'],
            ]);
        }
        $jwtToken = $user->createToken($credentials['device_name'] ?? 'default-device')->plainTextToken;

        return ApiResponse::success($jwtToken, 'Successfully logged in!');
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return ApiResponse::deleted(null, 'Successfully logged out');
    }
}
