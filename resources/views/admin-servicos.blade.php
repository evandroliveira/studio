@extends('layouts.admin')

@php
    $activePage = 'servicos';
@endphp

@section('page_title', 'Servicos')
@section('title', 'Servicos')
@section('subtitle', 'Gerencie catalogo, valores e duracao dos servicos em uma area dedicada do admin.')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-4">
            <div class="admin-stat p-3 h-100">
                <div class="admin-stat-label text-muted">Catalogo ativo</div>
                <div class="admin-stat-value">{{ $dashboardMetrics['servicos'] }}</div>
                <div class="muted-copy small">servico(s) cadastrado(s)</div>
            </div>
        </div>
        <div class="col-12 col-lg-8">
            <div class="admin-card p-4 h-100">
                <div class="section-eyebrow text-muted mb-2">Cadastro</div>
                <h2 class="h4 fw-bold mb-1">Novo servico</h2>
                <p class="muted-copy mb-3">Cadastre nome, valor e duracao para manter a agenda e o faturamento consistentes.</p>

                <form method="POST" action="{{ route('admin.servicos.store') }}" class="row g-2">
                    @csrf
                    <div class="col-12 col-lg-4">
                        <input type="text" class="form-control" name="nome" placeholder="Nome do servico" value="{{ old('nome') }}" required>
                    </div>
                    <div class="col-12 col-lg-3">
                        <input type="number" class="form-control" name="valor" min="0" step="0.01" placeholder="Valor" value="{{ old('valor') }}" required>
                    </div>
                    <div class="col-12 col-lg-3">
                        <input type="time" class="form-control" name="duracao" step="60" value="{{ old('duracao') }}">
                    </div>
                    <div class="col-12 col-lg-2">
                        <button type="submit" class="btn btn-dark w-100">Salvar servico</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="admin-card p-4">
        <div class="section-eyebrow text-muted mb-2">Catalogo</div>
        <h2 class="h4 fw-bold mb-3">Lista de servicos</h2>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Servico</th>
                        <th>Valor</th>
                        <th>Duracao</th>
                        <th class="text-end">Acao</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($servicos as $servico)
                        <tr>
                            <td><strong>{{ $servico->nome }}</strong></td>
                            <td>R$ {{ number_format($servico->valor, 2, ',', '.') }}</td>
                            <td>{{ $servico->duracao ? substr($servico->duracao, 0, 5) : '-' }}</td>
                            <td class="text-end">
                                <div class="table-actions ms-auto">
                                    <form method="POST" action="{{ route('admin.servicos.update', $servico) }}" class="d-grid gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" class="form-control form-control-sm" name="nome" value="{{ $servico->nome }}" required>
                                        <input type="number" class="form-control form-control-sm" name="valor" min="0" step="0.01" value="{{ $servico->valor }}" required>
                                        <input type="time" class="form-control form-control-sm" name="duracao" step="60" value="{{ $servico->duracao ? substr($servico->duracao, 0, 5) : '' }}">
                                        <button class="btn btn-sm btn-outline-dark" type="submit">Atualizar</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.servicos.destroy', $servico) }}" onsubmit="return confirm('Excluir este servico?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="muted-copy">Nenhum servico cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection