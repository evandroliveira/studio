<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\GoogleTokenVerifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class GoogleLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_login_creates_a_cliente_user(): void
    {
        config()->set('services.google.client_id', 'google-client-id.apps.googleusercontent.com');

        $this->mock(GoogleTokenVerifier::class, function (MockInterface $mock): void {
            $mock->shouldReceive('verify')
                ->once()
                ->with('google-token')
                ->andReturn([
                    'sub' => 'google-user-123',
                    'email' => 'cliente.google@example.com',
                    'email_verified' => true,
                    'name' => 'Cliente Google',
                    'picture' => 'https://example.com/avatar.png',
                ]);
        });

        $response = $this->postJson(route('login.google'), [
            'credential' => 'google-token',
            'remember' => true,
        ]);

        $response->assertOk()->assertJson([
            'redirect' => route('agendamento.create'),
        ]);

        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'cliente.google@example.com',
            'role' => User::ROLE_CLIENTE,
            'google_id' => 'google-user-123',
        ]);
    }

    public function test_google_login_links_existing_owner_and_promotes_admin_role(): void
    {
        config()->set('services.google.client_id', 'google-client-id.apps.googleusercontent.com');
        config()->set('auth.owner_email', 'admin@studio.com');

        User::factory()->create([
            'name' => 'Dona do Salao',
            'email' => 'admin@studio.com',
            'role' => User::ROLE_CLIENTE,
            'google_id' => null,
        ]);

        $this->mock(GoogleTokenVerifier::class, function (MockInterface $mock): void {
            $mock->shouldReceive('verify')
                ->once()
                ->with('owner-google-token')
                ->andReturn([
                    'sub' => 'google-owner-456',
                    'email' => 'admin@studio.com',
                    'email_verified' => true,
                    'name' => 'Dona do Salao',
                    'picture' => 'https://example.com/admin.png',
                ]);
        });

        $response = $this->postJson(route('login.google'), [
            'credential' => 'owner-google-token',
        ]);

        $response->assertOk()->assertJson([
            'redirect' => route('admin.dashboard'),
        ]);

        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'admin@studio.com',
            'role' => User::ROLE_ADMIN,
            'google_id' => 'google-owner-456',
        ]);
    }
}