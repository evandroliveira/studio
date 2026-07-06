<?php

namespace App\Http\Controllers;

use App\Mail\AgendamentoConfirmadoMail;
use App\Models\Agendamento;
use App\Models\Funcionario;
use App\Models\Servico;
use App\Support\AgendamentoHorario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class OwnerController extends Controller
{
    public function dashboard()
    {
        $data = $this->buildAdminOverviewData();

        return view('admin-dashboard', $data);
    }

    public function agendamentos(Request $request)
    {
        $data = $this->buildAdminOverviewData();

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

        return view('admin-agendamentos', array_merge($data, [
            'agendamentos' => $agendamentos,
        ]));
    }

    public function profissionais()
    {
        $data = $this->buildAdminOverviewData();

        return view('admin-profissionais', [
            'funcionarios' => $data['funcionarios'],
            'dashboardMetrics' => $data['dashboardMetrics'],
        ]);
    }

    public function servicos()
    {
        $data = $this->buildAdminOverviewData();

        return view('admin-servicos', [
            'servicos' => $data['servicos'],
            'dashboardMetrics' => $data['dashboardMetrics'],
        ]);
    }

    private function buildAdminOverviewData(): array
    {
        $today = Carbon::today();
        $now = Carbon::now();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();
        $statusColumnAvailable = Schema::hasColumn('agendamentos', 'status');

        $servicos = Servico::orderBy('nome')->get();
        $funcionarios = Funcionario::orderBy('nome')->get();

        $agendaHoje = Agendamento::with(['user', 'servicoModel', 'funcionario'])
            ->when($statusColumnAvailable, function ($query) {
                $query->where(function ($statusQuery) {
                    $statusQuery->whereNull('status')->orWhere('status', '!=', 'cancelado');
                });
            })
            ->whereDate('data', $today->toDateString())
            ->orderBy('horario')
            ->get();

        $agendaHojePorProfissional = $agendaHoje->groupBy(function (Agendamento $agendamento) {
            return $agendamento->funcionario->nome ?? $agendamento->profissional ?? 'Sem profissional';
        });

        $proximosAgendamentos = Agendamento::with(['user', 'servicoModel', 'funcionario'])
            ->when($statusColumnAvailable, function ($query) {
                $query->where(function ($statusQuery) {
                    $statusQuery->whereNull('status')->orWhere('status', '!=', 'cancelado');
                });
            })
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
            ->when($statusColumnAvailable, function ($query) {
                $query->where(function ($statusQuery) {
                    $statusQuery->whereNull('status')->orWhere('status', '!=', 'cancelado');
                });
            })
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

        $mailStatus = $this->resolveMailStatus();

        return compact(
            'servicos',
            'funcionarios',
            'agendaHoje',
            'agendaHojePorProfissional',
            'proximosAgendamentos',
            'dashboardMetrics',
            'statusColumnAvailable',
            'mailStatus'
        );
    }

    private function resolveMailStatus(): array
    {
        $driver = (string) config('mail.default', 'log');
        $smtpHost = (string) config('mail.mailers.smtp.host');
        $smtpPort = (string) config('mail.mailers.smtp.port');
        $smtpUsername = (string) config('mail.mailers.smtp.username');
        $fromAddress = (string) config('mail.from.address');

        $ready = in_array($driver, ['smtp', 'failover'], true)
            && filled($smtpHost)
            && $smtpHost !== '127.0.0.1'
            && filled($smtpUsername)
            && filled($fromAddress)
            && $fromAddress !== 'hello@example.com';

        return [
            'driver' => $driver,
            'smtp_host' => $smtpHost,
            'smtp_port' => $smtpPort,
            'from_address' => $fromAddress,
            'smtp_username' => $smtpUsername,
            'ready' => $ready,
        ];
    }

    public function todayAgenda()
    {
        $today = Carbon::today();
        $statusColumnAvailable = Schema::hasColumn('agendamentos', 'status');

        $agendaHoje = Agendamento::with(['user', 'servicoModel', 'funcionario'])
            ->whereDate('data', $today->toDateString())
            ->orderBy('horario')
            ->get();

        $agendaHojeResumo = [
            'total' => $agendaHoje->count(),
            'pendente' => $agendaHoje->filter(fn (Agendamento $agendamento) => ($agendamento->status ?? 'pendente') === 'pendente')->count(),
            'confirmado' => $agendaHoje->where('status', 'confirmado')->count(),
            'cancelado' => $agendaHoje->where('status', 'cancelado')->count(),
        ];

        return view('admin-agenda-today', compact('agendaHoje', 'agendaHojeResumo', 'today', 'statusColumnAvailable'));
    }

    public function updateStatus(Request $request, Agendamento $agendamento)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:confirmado,cancelado'],
        ]);

        if (! Schema::hasColumn('agendamentos', 'status')) {
            return back()->withErrors([
                'agendamentos' => 'Atualize o banco de dados executando as migrations para confirmar ou cancelar horarios.',
            ]);
        }

        $statusAnterior = $agendamento->status ?? 'pendente';

        $agendamento->update([
            'status' => $validated['status'],
        ]);

        $mensagem = $validated['status'] === 'confirmado'
            ? 'Horario confirmado com sucesso.'
            : 'Horario cancelado com sucesso.';

        $warning = null;

        if ($validated['status'] === 'confirmado' && $statusAnterior !== 'confirmado') {
            $agendamento->refresh()->loadMissing(['user', 'servicoModel', 'funcionario']);

            if ($agendamento->user?->email) {
                try {
                    Mail::to($agendamento->user->email)->send(new AgendamentoConfirmadoMail($agendamento));
                    $mensagem = 'Horario confirmado com sucesso. A cliente recebera um e-mail com os detalhes do agendamento.';
                } catch (\Throwable $e) {
                    report($e);
                    $warning = 'Horario confirmado, mas nao foi possivel enviar o e-mail de confirmacao agora.';
                }
            }
        }

        $response = back()->with('success', $mensagem);

        if ($warning) {
            $response->with('warning', $warning);
        }

        return $response;
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
        $dadosAtualizacao = [
            'servico_id' => $servico->id,
            'funcionario_id' => $funcionario->id,
            'data' => $validated['data'],
            'horario' => $horarioInicio,
            'servico' => $servico->nome,
            'profissional' => $funcionario->nome,
        ];

        if (Schema::hasColumn('agendamentos', 'status')) {
            $dadosAtualizacao['status'] = 'pendente';
        }

        $agendamento->update($dadosAtualizacao);

        return back()->with('success', 'Agendamento atualizado com sucesso.');
    }

    public function destroyAgendamento(Agendamento $agendamento)
    {
        $agendamento->delete();

        return back()->with('success', 'Agendamento cancelado com sucesso.');
    }
}
