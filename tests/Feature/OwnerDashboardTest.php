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

    public function test_admin_dashboard_named_route_points_to_main_panel(): void
    {
        $this->assertSame('/admin/painel', route('admin.dashboard', [], false));
        $this->assertSame('/admin/dashboard', route('admin.dashboard.legacy', [], false));
    }

    public function test_owner_dashboard_displays_summary_and_schedule_data(): void
    {
        $owner = User::factory()->admin()->create([
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
            'especialidade' => 'Colorista',
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

        $response = $this->actingAs($owner)->get('/admin/painel');

        $response->assertOk();
        $response->assertSee('Painel administrativo');
        $response->assertSee('Faturamento previsto do mes');
        $response->assertSee('Modo atual de e-mail');
        $response->assertSee('Cliente Teste');
        $response->assertSee('Corte premium');
        $response->assertSee('Nayara');
        $response->assertSee('Colorista');
        $response->assertSee('Clientes agendados');
        $response->assertSee('Profissionais');
        $response->assertSee('Servicos');
    }

    public function test_owner_can_open_profissionais_page(): void
    {
        $owner = User::factory()->admin()->create([
            'email' => 'admin@studio.com',
        ]);

        Funcionario::create([
            'nome' => 'Nayara',
            'especialidade' => 'Colorista',
        ]);

        $response = $this->actingAs($owner)->get('/admin/profissionais');

        $response->assertOk();
        $response->assertSee('Novo profissional');
        $response->assertSee('Colorista');
        $response->assertSee('Salvar profissional');
    }

    public function test_owner_can_open_servicos_page(): void
    {
        $owner = User::factory()->admin()->create([
            'email' => 'admin@studio.com',
        ]);

        Servico::create([
            'nome' => 'Escova modelada',
            'valor' => 95,
            'duracao' => '00:45:00',
        ]);

        $response = $this->actingAs($owner)->get('/admin/servicos');

        $response->assertOk();
        $response->assertSee('Novo servico');
        $response->assertSee('Escova modelada');
        $response->assertSee('Salvar servico');
    }

    public function test_owner_can_open_agendamentos_page(): void
    {
        $owner = User::factory()->admin()->create([
            'email' => 'admin@studio.com',
        ]);

        $cliente = User::factory()->create([
            'name' => 'Cliente Agenda',
        ]);

        $servico = Servico::create([
            'nome' => 'Corte premium',
            'valor' => 120,
        ]);

        $funcionario = Funcionario::create([
            'nome' => 'Nayara',
            'especialidade' => 'Colorista',
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

        $response = $this->actingAs($owner)->get('/admin/agendamentos');

        $response->assertOk();
        $response->assertSee('Clientes agendados');
        $response->assertSee('Cliente Agenda');
        $response->assertSee('Confirmar horario');
    }

    public function test_owner_daily_schedule_view_displays_today_appointments(): void
    {
        $owner = User::factory()->admin()->create([
            'name' => 'Franciele',
            'email' => 'admin@studio.com',
        ]);

        $cliente = User::factory()->create([
            'name' => 'Cliente Agenda',
        ]);

        $servico = Servico::create([
            'nome' => 'Escova modelada',
            'valor' => 95,
            'duracao' => '00:45:00',
        ]);

        $funcionario = Funcionario::create([
            'nome' => 'Nayara',
            'especialidade' => 'Escovista',
        ]);

        Agendamento::create([
            'user_id' => $cliente->id,
            'servico_id' => $servico->id,
            'funcionario_id' => $funcionario->id,
            'data' => now()->toDateString(),
            'horario' => '15:00:00',
            'servico' => $servico->nome,
            'profissional' => $funcionario->nome,
        ]);

        $response = $this->actingAs($owner)->get('/admin/agendamentos/hoje');

        $response->assertOk();
        $response->assertSee('Agenda do dia');
        $response->assertSee('Cliente Agenda');
        $response->assertSee('Escova modelada');
        $response->assertSee('Confirmar horario');
        $response->assertSee('Cancelar horario');
        $response->assertSee('Escovista');
    }

    public function test_old_dona_dashboard_route_is_not_available_anymore(): void
    {
        $owner = User::factory()->admin()->create([
            'email' => 'admin@studio.com',
        ]);

        $response = $this->actingAs($owner)->get('/dona/painel');

        $response->assertNotFound();
    }
}