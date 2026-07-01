<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Funcionario;
use App\Models\Servico;
use App\Support\AgendamentoHorario;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function dashboard(Request $request)
    {
        $today = Carbon::today();
        $now = Carbon::now();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();

        $servicos = Servico::orderBy('nome')->get();
        $funcionarios = Funcionario::orderBy('nome')->get();

        $agendaHoje = Agendamento::with(['user', 'servicoModel', 'funcionario'])
            ->whereDate('data', $today->toDateString())
            ->orderBy('horario')
            ->get();

        $agendaHojePorProfissional = $agendaHoje->groupBy(function (Agendamento $agendamento) {
            return $agendamento->funcionario->nome ?? $agendamento->profissional ?? 'Sem profissional';
        });

        $proximosAgendamentos = Agendamento::with(['user', 'servicoModel', 'funcionario'])
            ->where(function ($query) use ($today, $now) {
                $query->whereDate('data', '>', $today->toDateString())
                    ->orWhere(function ($sameDayQuery) use ($today, $now) {
                        $sameDayQuery
                            ->whereDate('data', $today->toDateString())
                            ->where('horario', '>=', $now->format('H:i:s'));
                    });
            })
            ->orderBy('data')
            ->orderBy('horario')
            ->limit(6)
            ->get();

        $agendamentosDoMes = Agendamento::with(['user', 'servicoModel', 'funcionario'])
            ->whereBetween('data', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->orderBy('data')
            ->orderBy('horario')
            ->get();

        $faturamentoPrevistoMes = $agendamentosDoMes->sum(function (Agendamento $agendamento) {
            return (float) ($agendamento->servicoModel->valor ?? 0);
        });

        $dashboardMetrics = [
            'servicos' => $servicos->count(),
            'funcionarios' => $funcionarios->count(),
            'agenda_hoje' => $agendaHoje->count(),
            'clientes_mes' => $agendamentosDoMes->pluck('user_id')->filter()->unique()->count(),
            'faturamento_previsto_mes' => $faturamentoPrevistoMes,
            'ticket_medio_mes' => $agendamentosDoMes->isNotEmpty()
                ? $faturamentoPrevistoMes / $agendamentosDoMes->count()
                : 0,
        ];

        $agendamentosQuery = Agendamento::with(['user', 'servicoModel', 'funcionario'])
            ->orderBy('data', 'desc')
            ->orderBy('horario');

        if ($request->filled('cliente')) {
            $cliente = trim($request->string('cliente')->toString());

            $agendamentosQuery->whereHas('user', function ($query) use ($cliente) {
                $query->where('name', 'like', '%'.$cliente.'%');
            });
        }

        if ($request->filled('data')) {
            $agendamentosQuery->where('data', $request->string('data')->toString());
        }

        if ($request->filled('funcionario_id')) {
            $agendamentosQuery->where('funcionario_id', $request->integer('funcionario_id'));
        }

        $agendamentos = $agendamentosQuery->paginate(20)->withQueryString();

        return view('owner-dashboard', compact(
            'servicos',
            'funcionarios',
            'agendamentos',
            'agendaHoje',
            'agendaHojePorProfissional',
            'proximosAgendamentos',
            'dashboardMetrics'
        ));
    }

    public function updateAgendamento(Request $request, Agendamento $agendamento)
    {
        $validated = $request->validate([
            'data' => ['required', 'date', 'after_or_equal:today'],
            'horario' => ['required', 'date_format:H:i'],
            'servico_id' => ['required', 'exists:servicos,id'],
            'funcionario_id' => ['required', 'exists:funcionarios,id'],
        ]);

        $servico = Servico::findOrFail($validated['servico_id']);
        $horarioInicio = AgendamentoHorario::normalizeClockTime($validated['horario']);
        $duracaoServico = AgendamentoHorario::durationToMinutes($servico->duracao);

        $conflito = Agendamento::with('servicoModel')
            ->where('id', '!=', $agendamento->id)
            ->where('data', $validated['data'])
            ->where('funcionario_id', $validated['funcionario_id'])
            ->get()
            ->contains(function (Agendamento $outroAgendamento) use ($validated, $horarioInicio, $duracaoServico) {
                $duracaoAgendamento = AgendamentoHorario::durationToMinutes($outroAgendamento->servicoModel?->duracao);

                return AgendamentoHorario::appointmentOverlaps(
                    $validated['data'],
                    $horarioInicio,
                    $duracaoServico,
                    $outroAgendamento->data,
                    $outroAgendamento->horario,
                    $duracaoAgendamento
                );
            });

        if ($conflito) {
            return back()->withErrors([
                'agendamentos' => 'Nao foi possivel atualizar. Esta profissional ja possui um agendamento nesse horario.',
            ]);
        }
        $funcionario = Funcionario::findOrFail($validated['funcionario_id']);

        $agendamento->update([
            'servico_id' => $servico->id,
            'funcionario_id' => $funcionario->id,
            'data' => $validated['data'],
            'horario' => $horarioInicio,
            'servico' => $servico->nome,
            'profissional' => $funcionario->nome,
        ]);

        return back()->with('success', 'Agendamento atualizado com sucesso.');
    }

    public function destroyAgendamento(Agendamento $agendamento)
    {
        $agendamento->delete();

        return back()->with('success', 'Agendamento cancelado com sucesso.');
    }
}
