<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SSOAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.sso.url' => 'http://sso.test',
            'services.sso.client_id' => 'test-client-id',
            'services.sso.client_secret' => 'test-client-secret',
            'services.sso.redirect_uri' => 'http://localhost:8000/callback',
        ]);
    }

    public function test_login_route_renders_local_login_page()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('SIGN IN WITH SSO');
    }

    public function test_sso_login_route_redirects_to_sso_server()
    {
        $response = $this->get('/login/sso');

        $response->assertStatus(302);
        $redirectUrl = $response->headers->get('Location');
        $this->assertStringStartsWith('http://sso.test/oauth/authorize', $redirectUrl);
        $this->assertStringContainsString('client_id=test-client-id', $redirectUrl);
        $this->assertStringContainsString('redirect_uri=' . urlencode('http://localhost:8000/callback'), $redirectUrl);
        $this->assertStringContainsString('response_type=code', $redirectUrl);
        $this->assertStringContainsString('state=', $redirectUrl);

        $this->assertNotEmpty(session('oauth_state'));
    }

    public function test_callback_fails_with_invalid_state()
    {
        session(['oauth_state' => 'expected-state']);

        $response = $this->get('/callback?code=some-code&state=wrong-state');

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['oauth']);
    }

    public function test_callback_fails_with_missing_code()
    {
        session(['oauth_state' => 'expected-state']);

        $response = $this->get('/callback?state=expected-state');

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['oauth']);
    }

    public function test_successful_sso_callback_logs_user_in()
    {
        session(['oauth_state' => 'expected-state']);

        Http::fake([
            'http://sso.test/api/oauth/token' => Http::response([
                'access_token' => 'dummy-access-token',
                'refresh_token' => 'dummy-refresh-token',
            ], 200),
            'http://sso.test/api/oauth/user' => Http::response([
                'id' => 123,
                'first_name' => 'John',
                'surname' => 'Doe',
                'email' => 'john.doe@example.com',
            ], 200),
        ]);

        $response = $this->get('/callback?code=valid-code&state=expected-state');

        $response->assertRedirect('/');

        $this->assertDatabaseHas('user', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);

        $user = User::where('email', 'john.doe@example.com')->first();
        $this->assertNotNull($user);
        $this->assertAuthenticatedAs($user);

        // Accessing home now works because we are authenticated
        $this->get('/')->assertStatus(200);
    }

    public function test_logout_redirects_to_sso_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertStatus(302);
        $redirectUrl = $response->headers->get('Location');
        $this->assertStringStartsWith('http://sso.test/logout', $redirectUrl);
        $this->assertStringContainsString('client_id=test-client-id', $redirectUrl);
        $this->assertStringContainsString('redirect_uri=' . urlencode(route('login')), $redirectUrl);

        $this->assertGuest();
    }

    public function test_verify_sso_session_middleware_logs_out_user_if_token_revoked()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        session(['sso_access_token' => 'dummy-access-token']);

        // Fake the user info request to return a failed response (e.g. 401 Unauthenticated)
        Http::fake([
            'http://sso.test/api/oauth/user' => Http::response(['error' => 'Unauthenticated'], 401),
        ]);

        // Accessing home should now redirect to login because of the revoked token
        $response = $this->get('/');

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['oauth']);
        $this->assertGuest();
    }
}
