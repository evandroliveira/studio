<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Funcionario;
use App\Models\Servico;
use App\Support\AgendamentoHorario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class AgendamentoController extends Controller
{
    public function create()
    {
        $setupError = $this->agendamentoSetupError();

        if ($setupError) {
            return view('agendamento', [
                'servicos' => collect(),
                'funcionarios' => collect(),
                'setupError' => $setupError,
            ]);
        }

        $servicos = Servico::orderBy('nome')->get();
        $funcionarios = Funcionario::orderBy('nome')->get();

        return view('agendamento', compact('servicos', 'funcionarios'));
    }

    public function store(Request $request)
    {
        $setupError = $this->agendamentoSetupError();

        if ($setupError) {
            return back()
                ->withInput()
                ->withErrors(['agendamento' => $setupError]);
        }

        $validated = $request->validate([
            'data' => ['required', 'date', 'after_or_equal:today'],
            'horario' => ['required', 'date_format:H:i'],
            'servico_id' => ['required', 'exists:servicos,id'],
            'funcionario_id' => ['required', 'exists:funcionarios,id'],
        ]);

        $servico = Servico::findOrFail($validated['servico_id']);
        $horarioInicio = AgendamentoHorario::normalizeClockTime($validated['horario']);
        $duracaoServico = AgendamentoHorario::durationToMinutes($servico->duracao);

        $disponibilidade = $this->resolveDisponibilidade(
            $validated['data'],
            (int) $validated['funcionario_id'],
            $duracaoServico
        );

        $horarioSelecionado = substr($horarioInicio, 0, 5);

        if (! in_array($horarioSelecionado, $disponibilidade['disponiveis'], true)) {
            return back()
                ->withInput()
                ->withErrors([
                    'horario' => 'Este horario nao esta disponivel para essa profissional. Escolha um dos proximos horarios sugeridos.',
                ])
                ->with('availabilitySuggestions', $this->buildAvailabilitySuggestions(
                    $validated['data'],
                    (int) $validated['funcionario_id'],
                    $duracaoServico,
                    $horarioSelecionado,
                    $disponibilidade['disponiveis']
                ));
        }

        $funcionario = Funcionario::findOrFail($validated['funcionario_id']);

        Agendamento::create([
            'user_id' => auth()->id(),
            'servico_id' => $servico->id,
            'funcionario_id' => $funcionario->id,
            'data' => $validated['data'],
            'horario' => $horarioInicio,
            'servico' => $servico->nome,
            'profissional' => $funcionario->nome,
        ]);

        return redirect()->route('agendamento.create')->with('success', 'Agendamento realizado com sucesso!');
    }

    public function meusAgendamentos()
    {
        $setupError = $this->agendamentoSetupError();

        if ($setupError) {
            return view('meus-agendamentos', [
                'agendamentos' => collect(),
                'setupError' => $setupError,
            ]);
        }

        $agendamentos = Agendamento::with(['servicoModel', 'funcionario'])
            ->where('user_id', auth()->id())
            ->orderBy('data', 'desc')
            ->orderBy('horario')
            ->get();

        return view('meus-agendamentos', compact('agendamentos'));
    }

    public function horariosDisponiveis(Request $request)
    {
        $setupError = $this->agendamentoSetupError();

        if ($setupError) {
            return response()->json([
                'message' => $setupError,
                'disponiveis' => [],
                'ocupados' => [],
                'sugestoes' => [
                    'same_day' => [],
                    'next_days' => [],
                ],
            ], 503);
        }

        $validated = $request->validate([
            'data' => ['required', 'date', 'after_or_equal:today'],
            'funcionario_id' => ['required', 'exists:funcionarios,id'],
            'servico_id' => ['required', 'exists:servicos,id'],
        ]);

        $servico = Servico::findOrFail($validated['servico_id']);
        $duracaoServico = AgendamentoHorario::durationToMinutes($servico->duracao);

        $disponibilidade = $this->resolveDisponibilidade(
            $validated['data'],
            (int) $validated['funcionario_id'],
            $duracaoServico
        );

        $horarioSelecionado = trim($request->string('horario')->toString());
        $horarioSelecionado = $horarioSelecionado !== ''
            ? substr(AgendamentoHorario::normalizeClockTime($horarioSelecionado), 0, 5)
            : null;

        $sugestoes = [
            'same_day' => [],
            'next_days' => [],
        ];

        if ($horarioSelecionado !== null && ! in_array($horarioSelecionado, $disponibilidade['disponiveis'], true)) {
            $sugestoes = $this->buildAvailabilitySuggestions(
                $validated['data'],
                (int) $validated['funcionario_id'],
                $duracaoServico,
                $horarioSelecionado,
                $disponibilidade['disponiveis']
            );
        }

        return response()->json([
            'disponiveis' => $disponibilidade['disponiveis'],
            'ocupados' => $disponibilidade['ocupados'],
            'sugestoes' => $sugestoes,
        ]);
    }

    private function resolveDisponibilidade(string $data, int $funcionarioId, int $duracaoServico): array
    {
        $agendamentosExistentes = Agendamento::with('servicoModel')
            ->where('data', $data)
            ->where('funcionario_id', $funcionarioId)
            ->get();

        $slots = $this->gerarSlots();
        $agora = Carbon::now();
        $ehHoje = Carbon::parse($data)->isSameDay($agora);

        $disponiveis = collect($slots)
            ->reject(function ($slot) use ($agendamentosExistentes, $ehHoje, $agora, $data, $duracaoServico) {
                if ($ehHoje) {
                    $dataHoraSlot = Carbon::parse($data . ' ' . $slot . ':00');

                    if ($dataHoraSlot->lessThanOrEqualTo($agora)) {
                        return true;
                    }
                }

                $slotInicio = AgendamentoHorario::normalizeClockTime($slot);

                return $agendamentosExistentes->contains(function (Agendamento $agendamento) use ($data, $slotInicio, $duracaoServico) {
                    $duracaoAgendamento = AgendamentoHorario::durationToMinutes($agendamento->servicoModel?->duracao);

                    return AgendamentoHorario::appointmentOverlaps(
                        $data,
                        $slotInicio,
                        $duracaoServico,
                        $agendamento->data,
                        $agendamento->horario,
                        $duracaoAgendamento
                    );
                });
            })
            ->values()
            ->all();

        return [
            'disponiveis' => $disponiveis,
            'ocupados' => $agendamentosExistentes
                ->pluck('horario')
                ->map(fn ($hora) => substr((string) $hora, 0, 5))
                ->values()
                ->all(),
        ];
    }

    private function buildAvailabilitySuggestions(
        string $data,
        int $funcionarioId,
        int $duracaoServico,
        ?string $horarioSelecionado = null,
        ?array $disponiveisMesmoDia = null
    ): array {
        $horarioSelecionado = $horarioSelecionado !== null && $horarioSelecionado !== ''
            ? substr(AgendamentoHorario::normalizeClockTime($horarioSelecionado), 0, 5)
            : null;

        $disponiveisMesmoDia ??= $this->resolveDisponibilidade($data, $funcionarioId, $duracaoServico)['disponiveis'];

        $sameDay = collect($this->orderSuggestedSlots($disponiveisMesmoDia, $horarioSelecionado))
            ->take(3)
            ->map(fn (string $slot) => [
                'data' => $data,
                'horario' => $slot,
            ])
            ->values()
            ->all();

        $nextDays = [];

        for ($offset = 1; $offset <= 7 && count($nextDays) < 3; $offset++) {
            $proximaData = Carbon::parse($data)->addDays($offset)->toDateString();
            $disponibilidadeProximoDia = $this->resolveDisponibilidade($proximaData, $funcionarioId, $duracaoServico);

            if ($disponibilidadeProximoDia['disponiveis'] === []) {
                continue;
            }

            $nextDays[] = [
                'data' => $proximaData,
                'horarios' => array_slice(
                    $this->orderSuggestedSlots($disponibilidadeProximoDia['disponiveis'], $horarioSelecionado),
                    0,
                    3
                ),
            ];
        }

        return [
            'same_day' => $sameDay,
            'next_days' => $nextDays,
        ];
    }

    private function orderSuggestedSlots(array $slots, ?string $horarioSelecionado): array
    {
        $orderedSlots = array_values(array_unique($slots));

        if ($horarioSelecionado === null || $horarioSelecionado === '') {
            return $orderedSlots;
        }

        usort($orderedSlots, function (string $left, string $right) use ($horarioSelecionado) {
            $leftMinutes = $this->clockToMinutes($left);
            $rightMinutes = $this->clockToMinutes($right);
            $selectedMinutes = $this->clockToMinutes($horarioSelecionado);

            $leftIsBefore = $leftMinutes < $selectedMinutes ? 1 : 0;
            $rightIsBefore = $rightMinutes < $selectedMinutes ? 1 : 0;

            if ($leftIsBefore !== $rightIsBefore) {
                return $leftIsBefore <=> $rightIsBefore;
            }

            $leftDistance = abs($leftMinutes - $selectedMinutes);
            $rightDistance = abs($rightMinutes - $selectedMinutes);

            if ($leftDistance !== $rightDistance) {
                return $leftDistance <=> $rightDistance;
            }

            return strcmp($left, $right);
        });

        return $orderedSlots;
    }

    private function clockToMinutes(string $clock): int
    {
        [$hours, $minutes] = array_map('intval', explode(':', substr(AgendamentoHorario::normalizeClockTime($clock), 0, 5)));

        return ($hours * 60) + $minutes;
    }

    private function gerarSlots(): array
    {
        $slots = [];
        $inicio = Carbon::createFromTimeString('08:00:00');
        $fim = Carbon::createFromTimeString('19:00:00');

        while ($inicio->lessThanOrEqualTo($fim)) {
            $slots[] = $inicio->format('H:i');
            $inicio->addMinutes(30);
        }

        return $slots;
    }

    private function agendamentoSetupError(): ?string
    {
        $requiredSchema = [
            'servicos' => ['nome', 'valor', 'duracao'],
            'funcionarios' => ['nome'],
            'agendamentos' => ['user_id', 'data', 'horario', 'servico', 'profissional', 'servico_id', 'funcionario_id'],
        ];

        $missing = [];

        try {
            foreach ($requiredSchema as $table => $columns) {
                if (! Schema::hasTable($table)) {
                    $missing[] = "tabela {$table}";
                    continue;
                }

                foreach ($columns as $column) {
                    if (! Schema::hasColumn($table, $column)) {
                        $missing[] = "coluna {$table}.{$column}";
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Fluxo de agendamento indisponivel por falha de banco.', [
                'exception' => $e::class,
                'message' => $e->getMessage(),
            ]);

            return 'O sistema de agendamento nao conseguiu acessar o banco de dados nesta hospedagem. Revise o .env de producao e limpe o cache do Laravel.';
        }

        if ($missing === []) {
            return null;
        }

        Log::warning('Fluxo de agendamento indisponivel por schema incompleto.', [
            'missing' => $missing,
        ]);

        return 'O sistema de agendamento ainda nao foi finalizado nesta hospedagem. Execute as migrations em producao e limpe o cache do Laravel.';
    }
}
