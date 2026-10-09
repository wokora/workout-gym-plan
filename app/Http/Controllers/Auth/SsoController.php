<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SsoController extends Controller
{
    public function handleCallback(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
            'redirect_uri' => 'nullable|string',
        ]);

        $code = $request->input('code');
        $redirectUri = $request->input('redirect_uri') ?: config('services.sso.redirect_uri');
        $ssoBase = rtrim(config('services.sso.base_url') ?: config('services.sso.url', 'http://localhost:8001'), '/');

        // Exchange authorization code for token
        $tokenResponse = Http::asForm()->post($ssoBase . '/api/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.sso.client_id'),
            'client_secret' => config('services.sso.client_secret'),
            'redirect_uri' => $redirectUri,
            'code' => $code,
        ]);

        if ($tokenResponse->failed()) {
            return response()->json([
                'message' => 'Failed to exchange authorization code with SSO provider.',
                'details' => $tokenResponse->json() ?? $tokenResponse->body()
            ], 400);
        }

        $tokenData = $tokenResponse->json();
        $accessToken = $tokenData['access_token'] ?? null;

        if (!$accessToken) {
            return response()->json(['message' => 'Access token not returned from SSO server.'], 400);
        }

        // Fetch user profile from SSO server
        $userResponse = Http::withToken($accessToken)->get($ssoBase . '/api/oauth/user');

        if ($userResponse->failed()) {
            return response()->json(['message' => 'Failed to fetch user details from SSO.'], 400);
        }

        $ssoUser = $userResponse->json();

        if (empty($ssoUser['email'])) {
            return response()->json(['message' => 'Invalid user data received from SSO (missing email).'], 400);
        }

        $ssoUuid = $ssoUser['uuid'] ?? null;
        $ssoEmail = $ssoUser['email'];

        $name = $ssoUser['name'] ?? null;
        if (empty($name)) {
            $firstName = $ssoUser['first_name'] ?? '';
            $surname = $ssoUser['surname'] ?? '';
            $name = trim($firstName . ' ' . $surname);
        }
        if (empty($name) && $ssoEmail) {
            $name = explode('@', $ssoEmail)[0];
        }

        // Find or create user
        $user = null;
        if (!empty($ssoUuid)) {
            $user = User::where('uuid', $ssoUuid)->first();
        }

        if (!$user) {
            $user = User::where('email', $ssoEmail)->first();
        }

        if ($user) {
            $user->name = $name ?: $user->name;
            if ($ssoUuid && empty($user->uuid)) {
                $user->uuid = $ssoUuid;
            }
            if ($user->email !== $ssoEmail) {
                $user->email = $ssoEmail;
            }
            $user->save();
        } else {
            $user = User::create([
                'uuid' => $ssoUuid ?? (string) Str::uuid(),
                'name' => $name ?: 'User',
                'email' => $ssoEmail,
                'password' => Hash::make(Str::random(32)),
            ]);
        }

        // Create local Sanctum personal access token
        $localToken = $user->createToken('user_access_token')->plainTextToken;

        return response()->json([
            'credentials' => [
                'access_token' => $localToken,
            ],
            'user' => new UserResource($user),
        ]);
    }
}
