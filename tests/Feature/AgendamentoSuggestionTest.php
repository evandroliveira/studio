<?php

namespace Tests\Feature;

use App\Models\Agendamento;
use App\Models\Funcionario;
use App\Models\Servico;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgendamentoSuggestionTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_suggests_available_slots_when_selected_time_conflicts(): void
    {
        $cliente = User::factory()->create();
        $servico = Servico::create(['nome' => 'Coloracao', 'valor' => 180, 'duracao' => '01:00:00']);
        $funcionario = Funcionario::create(['nome' => 'Franciele']);
        $data = now()->addDay()->toDateString();

        Agendamento::create([
            'user_id' => $cliente->id,
            'servico_id' => $servico->id,
            'funcionario_id' => $funcionario->id,
            'data' => $data,
            'horario' => '09:00:00',
            'servico' => $servico->nome,
            'profissional' => $funcionario->nome,
        ]);

        $response = $this->actingAs($cliente)
            ->from('/agendamento')
            ->post(route('agendamento.store'), [
                'data' => $data,
                'horario' => '09:00',
                'servico_id' => $servico->id,
                'funcionario_id' => $funcionario->id,
            ]);

        $response->assertRedirect('/agendamento');
        $response->assertSessionHasErrors(['horario']);
        $response->assertSessionHas('availabilitySuggestions', function (array $suggestions) {
            return ($suggestions['same_day'][0]['horario'] ?? null) === '10:00'
                && ! empty($suggestions['next_days'][0]['data'] ?? null)
                && ! empty($suggestions['next_days'][0]['horarios'] ?? []);
        });
    }

    public function test_horarios_endpoint_returns_suggestions_for_unavailable_selected_time(): void
    {
        $cliente = User::factory()->create();
        $servico = Servico::create(['nome' => 'Corte', 'valor' => 90, 'duracao' => '01:00:00']);
        $funcionario = Funcionario::create(['nome' => 'Nayara']);
        $data = now()->addDay()->toDateString();

        Agendamento::create([
            'user_id' => $cliente->id,
            'servico_id' => $servico->id,
            'funcionario_id' => $funcionario->id,
            'data' => $data,
            'horario' => '09:00:00',
            'servico' => $servico->nome,
            'profissional' => $funcionario->nome,
        ]);

        $response = $this->actingAs($cliente)->getJson(route('agendamento.horarios', [
            'data' => $data,
            'funcionario_id' => $funcionario->id,
            'servico_id' => $servico->id,
            'horario' => '09:00',
        ]));

        $response->assertOk();
        $response->assertJsonPath('sugestoes.same_day.0.horario', '10:00');
        $response->assertJsonStructure([
            'disponiveis',
            'ocupados',
            'sugestoes' => [
                'same_day',
                'next_days' => [
                    ['data', 'horarios'],
                ],
            ],
        ]);
    }
}