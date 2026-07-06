@extends('layouts.admin')

@php
    $activePage = 'profissionais';
@endphp

@section('page_title', 'Profissionais')
@section('title', 'Profissionais')
@section('subtitle', 'Cadastre e atualize a equipe em uma pagina propria, sem misturar com os demais controles do admin.')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-4">
            <div class="admin-stat p-3 h-100">
                <div class="admin-stat-label text-muted">Equipe ativa</div>
                <div class="admin-stat-value">{{ $dashboardMetrics['funcionarios'] }}</div>
                <div class="muted-copy small">profissional(is) cadastrada(s)</div>
            </div>
        </div>
        <div class="col-12 col-lg-8">
            <div class="admin-card p-4 h-100">
                <div class="section-eyebrow text-muted mb-2">Cadastro</div>
                <h2 class="h4 fw-bold mb-1">Novo profissional</h2>
                <p class="muted-copy mb-3">Cadastre nome e especialidade para manter a equipe organizada na area administrativa.</p>

                <form method="POST" action="{{ route('admin.funcionarios.store') }}" class="row g-2">
                    @csrf
                    <div class="col-12 col-lg-5">
                        <input type="text" class="form-control" name="nome" placeholder="Nome da profissional" value="{{ old('nome') }}" required>
                    </div>
                    <div class="col-12 col-lg-5">
                        <input type="text" class="form-control" name="especialidade" placeholder="Especialidade da profissional" value="{{ old('especialidade') }}" required>
                    </div>
                    <div class="col-12 col-lg-2">
                        <button type="submit" class="btn btn-dark w-100">Salvar profissional</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="admin-card p-4">
        <div class="section-eyebrow text-muted mb-2">Equipe cadastrada</div>
        <h2 class="h4 fw-bold mb-3">Lista de profissionais</h2>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Profissional</th>
                        <th>Especialidade</th>
                        <th class="text-end">Acao</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($funcionarios as $funcionario)
                        <tr>
                            <td><strong>{{ $funcionario->nome }}</strong></td>
                            <td>{{ $funcionario->especialidade ?? 'Nao informada' }}</td>
                            <td class="text-end">
                                <div class="table-actions ms-auto">
                                    <form method="POST" action="{{ route('admin.funcionarios.update', $funcionario) }}" class="d-grid gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" class="form-control form-control-sm" name="nome" value="{{ $funcionario->nome }}" required>
                                        <input type="text" class="form-control form-control-sm" name="especialidade" value="{{ $funcionario->especialidade }}" required>
                                        <button class="btn btn-sm btn-outline-dark" type="submit">Atualizar</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.funcionarios.destroy', $funcionario) }}" onsubmit="return confirm('Excluir este profissional?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="muted-copy">Nenhuma profissional cadastrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection