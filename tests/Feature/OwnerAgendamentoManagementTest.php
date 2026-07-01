<?php

namespace Tests\Feature;

use App\Models\Agendamento;
use App\Models\Funcionario;
use App\Models\Servico;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerAgendamentoManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_update_agendamento_from_dashboard(): void
    {
        $owner = User::factory()->create([
            'email' => 'admin@studio.com',
        ]);

        $cliente = User::factory()->create();
        $servicoOriginal = Servico::create(['nome' => 'Corte', 'valor' => 80, 'duracao' => '01:00:00']);
        $servicoNovo = Servico::create(['nome' => 'Escova', 'valor' => 120, 'duracao' => '00:45:00']);
        $funcionarioOriginal = Funcionario::create(['nome' => 'Franciele']);
        $funcionarioNovo = Funcionario::create(['nome' => 'Nayara']);

        $agendamento = Agendamento::create([
            'user_id' => $cliente->id,
            'servico_id' => $servicoOriginal->id,
            'funcionario_id' => $funcionarioOriginal->id,
            'data' => now()->addDay()->toDateString(),
            'horario' => '09:00:00',
            'servico' => $servicoOriginal->nome,
            'profissional' => $funcionarioOriginal->nome,
        ]);

        $response = $this->actingAs($owner)
            ->from('/dona/painel')
            ->put(route('owner.agendamentos.update', $agendamento), [
                'data' => now()->addDays(2)->toDateString(),
                'horario' => '14:30',
                'servico_id' => $servicoNovo->id,
                'funcionario_id' => $funcionarioNovo->id,
            ]);

        $response->assertRedirect('/dona/painel');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('agendamentos', [
            'id' => $agendamento->id,
            'data' => now()->addDays(2)->toDateString(),
            'horario' => '14:30:00',
            'servico_id' => $servicoNovo->id,
            'funcionario_id' => $funcionarioNovo->id,
            'servico' => 'Escova',
            'profissional' => 'Nayara',
        ]);
    }

    public function test_owner_cannot_move_agendamento_to_conflicting_slot(): void
    {
        $owner = User::factory()->create([
            'email' => 'admin@studio.com',
        ]);

        $cliente = User::factory()->create();
        $servico = Servico::create(['nome' => 'Corte', 'valor' => 80, 'duracao' => '01:00:00']);
        $funcionario = Funcionario::create(['nome' => 'Franciele']);
        $data = now()->addDay()->toDateString();

        $agendamentoBase = Agendamento::create([
            'user_id' => $cliente->id,
            'servico_id' => $servico->id,
            'funcionario_id' => $funcionario->id,
            'data' => $data,
            'horario' => '09:00:00',
            'servico' => $servico->nome,
            'profissional' => $funcionario->nome,
        ]);

        $agendamentoConflitante = Agendamento::create([
            'user_id' => $cliente->id,
            'servico_id' => $servico->id,
            'funcionario_id' => $funcionario->id,
            'data' => $data,
            'horario' => '09:30:00',
            'servico' => $servico->nome,
            'profissional' => $funcionario->nome,
        ]);

        $response = $this->actingAs($owner)
            ->from('/dona/painel')
            ->put(route('owner.agendamentos.update', $agendamentoBase), [
                'data' => $data,
                'horario' => '09:30',
                'servico_id' => $servico->id,
                'funcionario_id' => $funcionario->id,
            ]);

        $response->assertRedirect('/dona/painel');
        $response->assertSessionHasErrors(['agendamentos']);

        $this->assertDatabaseHas('agendamentos', [
            'id' => $agendamentoBase->id,
            'horario' => '09:00:00',
        ]);
        $this->assertDatabaseHas('agendamentos', [
            'id' => $agendamentoConflitante->id,
            'horario' => '09:30:00',
        ]);
    }

    public function test_owner_dashboard_horarios_considera_duracao_do_servico(): void
    {
        $owner = User::factory()->create([
            'email' => 'admin@studio.com',
        ]);

        $cliente = User::factory()->create();
        $servicoLongo = Servico::create(['nome' => 'Coloracao', 'valor' => 150, 'duracao' => '01:00:00']);
        $servicoCurto = Servico::create(['nome' => 'Finalize', 'valor' => 50, 'duracao' => '00:45:00']);
        $funcionario = Funcionario::create(['nome' => 'Franciele']);
        $data = now()->addDay()->toDateString();

        Agendamento::create([
            'user_id' => $cliente->id,
            'servico_id' => $servicoLongo->id,
            'funcionario_id' => $funcionario->id,
            'data' => $data,
            'horario' => '10:00:00',
            'servico' => $servicoLongo->nome,
            'profissional' => $funcionario->nome,
        ]);

        $response = $this->actingAs($owner)->getJson(route('agendamento.horarios', [
            'data' => $data,
            'funcionario_id' => $funcionario->id,
            'servico_id' => $servicoCurto->id,
        ]));

        $response->assertOk();

        $this->assertNotContains('10:30', $response->json('disponiveis'));
    }

    public function test_owner_can_cancel_agendamento_from_dashboard(): void
    {
        $owner = User::factory()->create([
            'email' => 'admin@studio.com',
        ]);

        $cliente = User::factory()->create();
        $servico = Servico::create(['nome' => 'Corte', 'valor' => 80]);
        $funcionario = Funcionario::create(['nome' => 'Franciele']);

        $agendamento = Agendamento::create([
            'user_id' => $cliente->id,
            'servico_id' => $servico->id,
            'funcionario_id' => $funcionario->id,
            'data' => now()->addDay()->toDateString(),
            'horario' => '11:00:00',
            'servico' => $servico->nome,
            'profissional' => $funcionario->nome,
        ]);

        $response = $this->actingAs($owner)
            ->from('/dona/painel')
            ->delete(route('owner.agendamentos.destroy', $agendamento));

        $response->assertRedirect('/dona/painel');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('agendamentos', [
            'id' => $agendamento->id,
        ]);
    }
}