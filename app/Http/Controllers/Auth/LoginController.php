<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'handleSSOCallback']);
    }

    /**
     * Redirect the user to the SSO authorization server.
     */
    public function redirectToSSO()
    {
        $state = Str::random(40);
        session(['oauth_state' => $state]);

        $query = http_build_query([
            'client_id' => config('services.sso.client_id'),
            'redirect_uri' => config('services.sso.redirect_uri'),
            'response_type' => 'code',
            'state' => $state,
        ]);

        return redirect(config('services.sso.url') . '/oauth/authorize?' . $query);
    }

    /**
     * Handle the callback from the SSO authorization server.
     */
    public function handleSSOCallback(Request $request)
    {
        $state = $request->input('state');
        $sessionState = session()->pull('oauth_state');

        if (empty($state) || $state !== $sessionState) {
            $expectedStr = $sessionState ?? '[empty/session lost]';
            $receivedStr = $state ?? '[empty]';
            return redirect()->route('login')->withErrors([
                'oauth' => "Invalid session state. Expected: {$expectedStr}, Received: {$receivedStr}. Please make sure you are accessing both apps using the exact same host (e.g., both http://localhost or both http://127.0.0.1)."
            ]);
        }

        $code = $request->input('code');
        if (empty($code)) {
            return redirect()->route('login')->withErrors(['oauth' => 'Authorization code missing.']);
        }

        // Exchange code for token
        $response = Http::asForm()->post(config('services.sso.url') . '/api/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.sso.client_id'),
            'client_secret' => config('services.sso.client_secret'),
            'redirect_uri' => config('services.sso.redirect_uri'),
            'code' => $code,
        ]);

        if ($response->failed()) {
            \Illuminate\Support\Facades\Log::error('SSO Token Exchange Failed', [
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body(),
            ]);
            $errorDetail = $response->json('error') ?? $response->body();
            if (empty($errorDetail)) {
                $errorDetail = 'Empty response (Status Code: ' . $response->status() . ')';
            }
            return redirect()->route('login')->withErrors(['oauth' => 'Failed to obtain access token: ' . $errorDetail]);
        }

        $tokenData = $response->json();
        $accessToken = $tokenData['access_token'] ?? null;

        if (!$accessToken) {
            return redirect()->route('login')->withErrors(['oauth' => 'Access token not returned from SSO server.']);
        }

        // Fetch user info
        $userResponse = Http::withToken($accessToken)->get(config('services.sso.url') . '/api/oauth/user');

        if ($userResponse->failed()) {
            return redirect()->route('login')->withErrors(['oauth' => 'Failed to retrieve user info: ' . $userResponse->body()]);
        }

        $userData = $userResponse->json();
        $email = $userData['email'] ?? null;

        if (!$email) {
            return redirect()->route('login')->withErrors(['oauth' => 'User email not returned from SSO server.']);
        }

        // Find or create local user
        $firstName = $userData['first_name'] ?? '';
        $surname = $userData['surname'] ?? '';
        $name = trim($firstName . ' ' . $surname);
        if (empty($name)) {
            $name = explode('@', $email)[0];
        }

        $user = \App\Models\User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => bcrypt(Str::random(16)),
            ]
        );

        session(['sso_access_token' => $accessToken]);

        auth()->login($user, true);

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Log the user out of the application and the SSO server.
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $ssoLogoutUrl = config('services.sso.url') . '/logout?client_id=' . config('services.sso.client_id') . '&redirect_uri=' . urlencode(route('login'));

        return redirect($ssoLogoutUrl);
    }
}

