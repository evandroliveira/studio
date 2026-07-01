<?php

namespace Tests\Feature;

use App\Models\Agendamento;
use App\Models\Funcionario;
use App\Models\Servico;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_dashboard_displays_summary_and_schedule_data(): void
    {
        $owner = User::factory()->create([
            'name' => 'Franciele',
            'email' => 'admin@studio.com',
        ]);

        $cliente = User::factory()->create([
            'name' => 'Cliente Teste',
        ]);

        $servico = Servico::create([
            'nome' => 'Corte premium',
            'valor' => 120,
        ]);

        $funcionario = Funcionario::create([
            'nome' => 'Nayara',
        ]);

        Agendamento::create([
            'user_id' => $cliente->id,
            'servico_id' => $servico->id,
            'funcionario_id' => $funcionario->id,
            'data' => now()->toDateString(),
            'horario' => '10:00:00',
            'servico' => $servico->nome,
            'profissional' => $funcionario->nome,
        ]);

        $response = $this->actingAs($owner)->get('/dona/painel');

        $response->assertOk();
        $response->assertSee('Studio Franciele Cesario');
        $response->assertSee('Faturamento previsto do mes');
        $response->assertSee('Cliente Teste');
        $response->assertSee('Corte premium');
        $response->assertSee('Nayara');
    }
}