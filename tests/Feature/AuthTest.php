<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test valid web login succeeds.
     */
    public function test_valid_web_login_succeeds(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('Secret123!'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'Secret123!',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test invalid web login is rejected.
     */
    public function test_invalid_web_login_is_rejected(): void
    {
        User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('Secret123!'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'WrongPassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test web logout logs out user and invalidates session.
     */
    public function test_web_logout_succeeds(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /**
     * Test valid API login returns Sanctum token and HTTP 200.
     */
    public function test_valid_api_login_returns_token(): void
    {
        $user = User::factory()->admin()->create([
            'email' => 'api_admin@test.com',
            'password' => Hash::make('Password123!'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'api_admin@test.com',
            'password' => 'Password123!',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Login successful',
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    'token',
                    'token_type',
                    'user' => ['id', 'name', 'email', 'role'],
                ],
            ]);
    }

    /**
     * Test invalid API login credentials return HTTP 401.
     */
    public function test_invalid_api_login_returns_401(): void
    {
        User::factory()->create([
            'email' => 'api_user@test.com',
            'password' => Hash::make('Password123!'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'api_user@test.com',
            'password' => 'IncorrectPassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid email or password.',
            ]);
    }

    /**
     * Test authenticated API logout revokes current token.
     */
    public function test_api_logout_revokes_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Token revoked successfully',
            ]);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
