<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Funcionario;
use App\Models\Servico;
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

        $conflito = Agendamento::where('data', $validated['data'])
            ->where('horario', $validated['horario'])
            ->where('funcionario_id', $validated['funcionario_id'])
            ->exists();

        if ($conflito) {
            return back()
                ->withInput()
                ->withErrors([
                    'horario' => 'Este profissional ja possui um agendamento nesse horario. Escolha outro horario.',
                ]);
        }

        $servico = Servico::findOrFail($validated['servico_id']);
        $funcionario = Funcionario::findOrFail($validated['funcionario_id']);

        Agendamento::create([
            'user_id' => auth()->id(),
            'servico_id' => $servico->id,
            'funcionario_id' => $funcionario->id,
            'data' => $validated['data'],
            'horario' => $validated['horario'],
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
            ], 503);
        }

        $validated = $request->validate([
            'data' => ['required', 'date', 'after_or_equal:today'],
            'funcionario_id' => ['required', 'exists:funcionarios,id'],
        ]);

        $ocupados = Agendamento::where('data', $validated['data'])
            ->where('funcionario_id', $validated['funcionario_id'])
            ->pluck('horario')
            ->map(fn ($hora) => substr((string) $hora, 0, 5))
            ->values();

        $slots = $this->gerarSlots();
        $agora = Carbon::now();
        $ehHoje = Carbon::parse($validated['data'])->isSameDay($agora);

        $disponiveis = collect($slots)
            ->reject(function ($slot) use ($ocupados, $ehHoje, $agora, $validated) {
                if ($ocupados->contains($slot)) {
                    return true;
                }

                if ($ehHoje) {
                    $dataHoraSlot = Carbon::parse($validated['data'] . ' ' . $slot . ':00');
                    return $dataHoraSlot->lessThanOrEqualTo($agora);
                }

                return false;
            })
            ->values();

        return response()->json([
            'disponiveis' => $disponiveis,
            'ocupados' => $ocupados,
        ]);
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
            'servicos' => ['nome', 'valor'],
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
