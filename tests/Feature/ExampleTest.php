<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example for unauthenticated redirect.
     *
     * @return void
     */
    public function testUnauthenticatedRedirect()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * A basic test example for authenticated home page.
     *
     * @return void
     */
    public function testAuthenticatedHome()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
    }
}

