<?php

namespace Tests\Feature;

use App\Models\Funcionario;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FuncionarioSpecialidadeTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_profissional_with_especialidade(): void
    {
        $owner = User::factory()->create([
            'email' => 'admin@studio.com',
        ]);

        $response = $this->actingAs($owner)
            ->from('/admin/painel')
            ->post(route('admin.funcionarios.store'), [
                'nome' => 'Nayara',
                'especialidade' => 'Colorista',
            ]);

        $response->assertRedirect('/admin/painel');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('funcionarios', [
            'nome' => 'Nayara',
            'especialidade' => 'Colorista',
        ]);
    }

    public function test_agendamento_page_shows_profissional_specialty(): void
    {
        $cliente = User::factory()->create();

        Funcionario::create([
            'nome' => 'Nayara',
            'especialidade' => 'Escovista',
        ]);

        $response = $this->actingAs($cliente)->get('/agendamento');

        $response->assertOk();
        $response->assertSee('Nayara');
        $response->assertSee('Escovista');
    }
}