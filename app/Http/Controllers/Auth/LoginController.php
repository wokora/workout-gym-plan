<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Handle API login. Supports email/password or prompts for SSO.
     */
    public function login(Request $request): JsonResponse
    {
        if ($request->has(['email', 'password'])) {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'The provided credentials do not match our records.'
                ], 422);
            }

            $token = $user->createToken('user_access_token')->plainTextToken;

            return response()->json([
                'credentials' => [
                    'access_token' => $token,
                ],
                'user' => new UserResource($user),
            ]);
        }

        return response()->json([
            'message' => 'SSO login is required. Please sign in using your SSO Account.'
        ], 403);
    }

    /**
     * Handle API logout.
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        return response()->json([
            'message' => 'Logged out successfully.'
        ]);
    }
}
