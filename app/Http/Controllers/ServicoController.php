<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Servico;
use Illuminate\Http\Request;

class ServicoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255', 'unique:servicos,nome'],
            'valor' => ['required', 'numeric', 'min:0'],
        ]);

        Servico::create($validated);

        return back()->with('success', 'Servico cadastrado com sucesso.');
    }

    public function update(Request $request, Servico $servico)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255', 'unique:servicos,nome,' . $servico->id],
            'valor' => ['required', 'numeric', 'min:0'],
        ]);

        $servico->update($validated);

        return back()->with('success', 'Servico atualizado com sucesso.');
    }

    public function destroy(Servico $servico)
    {
        $temAgendamento = Agendamento::where('servico_id', $servico->id)->exists();

        if ($temAgendamento) {
            return back()->withErrors([
                'servicos' => 'Nao e possivel excluir este servico porque ja existe agendamento vinculado.',
            ]);
        }

        $servico->delete();

        return back()->with('success', 'Servico removido com sucesso.');
    }
}
