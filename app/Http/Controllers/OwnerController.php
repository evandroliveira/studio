<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Funcionario;
use App\Models\Servico;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function dashboard(Request $request)
    {
        $servicos = Servico::orderBy('nome')->get();
        $funcionarios = Funcionario::orderBy('nome')->get();

        $agendamentosQuery = Agendamento::with(['user', 'servicoModel', 'funcionario'])
            ->orderBy('data', 'desc')
            ->orderBy('horario');

        if ($request->filled('data')) {
            $agendamentosQuery->where('data', $request->string('data')->toString());
        }

        if ($request->filled('funcionario_id')) {
            $agendamentosQuery->where('funcionario_id', $request->integer('funcionario_id'));
        }

        $agendamentos = $agendamentosQuery->paginate(20)->withQueryString();

        return view('owner-dashboard', compact('servicos', 'funcionarios', 'agendamentos'));
    }
}
