<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function test_only_admin_role_can_access_admin_area(): void
    {
        $cliente = User::factory()->create([
            'email' => 'admin@studio.com',
            'role' => 'cliente',
        ]);

        $response = $this->actingAs($cliente)->get('/admin/painel');

        $response->assertForbidden();

        $admin = User::factory()->admin()->create([
            'email' => 'qualquer@studio.com',
        ]);

        $response = $this->actingAs($admin)->get('/admin/painel');

        $response->assertOk();
    }
}