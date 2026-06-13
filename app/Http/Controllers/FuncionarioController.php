<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Funcionario;
use Illuminate\Http\Request;

class FuncionarioController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255', 'unique:funcionarios,nome'],
        ]);

        Funcionario::create($validated);

        return back()->with('success', 'Profissional cadastrado com sucesso.');
    }

    public function update(Request $request, Funcionario $funcionario)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255', 'unique:funcionarios,nome,' . $funcionario->id],
        ]);

        $funcionario->update($validated);

        return back()->with('success', 'Profissional atualizado com sucesso.');
    }

    public function destroy(Funcionario $funcionario)
    {
        $temAgendamento = Agendamento::where('funcionario_id', $funcionario->id)->exists();

        if ($temAgendamento) {
            return back()->withErrors([
                'funcionarios' => 'Nao e possivel excluir este profissional porque ja existe agendamento vinculado.',
            ]);
        }

        $funcionario->delete();

        return back()->with('success', 'Profissional removido com sucesso.');
    }
}
