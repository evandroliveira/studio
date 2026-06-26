<?php

namespace Tests\Feature;

use App\Models\Funcionario;
use App\Models\Servico;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AgendamentoSchemaGuardTest extends TestCase
{
    use RefreshDatabase;

    public function test_agendamento_create_shows_setup_error_when_schema_is_incomplete(): void
    {
        Schema::drop('servicos');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/agendamento');

        $response->assertOk();
        $response->assertSee('O sistema de agendamento ainda nao foi finalizado nesta hospedagem.');
    }

    public function test_agendamento_store_redirects_with_error_when_schema_is_incomplete(): void
    {
        $user = User::factory()->create();
        $servico = Servico::create([
            'nome' => 'Corte',
            'valor' => 80,
        ]);
        $funcionario = Funcionario::create([
            'nome' => 'Franciele',
        ]);

        Schema::drop('agendamentos');

        $response = $this->actingAs($user)
            ->from('/agendamento')
            ->post('/agendamento', [
                'data' => now()->addDay()->toDateString(),
                'horario' => '10:00',
                'servico_id' => $servico->id,
                'funcionario_id' => $funcionario->id,
            ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['agendamento']);
    }
}