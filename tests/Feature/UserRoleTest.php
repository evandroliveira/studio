<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_assigns_cliente_role_by_default(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Cliente Role',
            'email' => 'cliente@example.com',
            'password' => 'Senha12345',
            'password_confirmation' => 'Senha12345',
        ]);

        $response->assertRedirect(route('agendamento.create'));

        $this->assertDatabaseHas('users', [
            'email' => 'cliente@example.com',
            'role' => 'cliente',
        ]);
    }

    public function test_register_assigns_admin_role_to_owner_email(): void
    {
        config()->set('auth.owner_email', 'admin@studio.com');

        $response = $this->post(route('register.store'), [
            'name' => 'Dona do Salao',
            'email' => 'admin@studio.com',
            'password' => 'Senha12345',
            'password_confirmation' => 'Senha12345',
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => 'admin@studio.com',
            'role' => User::ROLE_ADMIN,
        ]);
    }

    public function test_owner_email_or_admin_role_can_access_admin_area(): void
    {
        config()->set('auth.owner_email', 'admin@studio.com');

        $cliente = User::factory()->create([
            'email' => 'cliente@studio.com',
            'role' => User::ROLE_CLIENTE,
        ]);

        $response = $this->actingAs($cliente)->get('/admin/painel');

        $response->assertForbidden();

        $owner = User::factory()->create([
            'email' => 'admin@studio.com',
            'role' => User::ROLE_CLIENTE,
        ]);

        $response = $this->actingAs($owner)->get('/admin/painel');

        $response->assertOk();

        $admin = User::factory()->admin()->create([
            'email' => 'qualquer@studio.com',
        ]);

        $response = $this->actingAs($admin)->get('/admin/painel');

        $response->assertOk();
    }

    public function test_login_promotes_owner_email_to_admin_role(): void
    {
        config()->set('auth.owner_email', 'admin@studio.com');

        User::factory()->create([
            'email' => 'admin@studio.com',
            'password' => Hash::make('Senha12345'),
            'role' => User::ROLE_CLIENTE,
        ]);

        $response = $this->post(route('login'), [
            'email' => 'admin@studio.com',
            'password' => 'Senha12345',
        ]);

        $response->assertRedirect('/admin/painel');

        $this->assertDatabaseHas('users', [
            'email' => 'admin@studio.com',
            'role' => User::ROLE_ADMIN,
        ]);
    }
}