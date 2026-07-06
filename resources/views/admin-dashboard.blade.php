@extends('layouts.admin')

@php
    $activePage = 'dashboard';
@endphp

@section('page_title', 'Painel administrativo')
@section('title', 'Painel administrativo')
@section('subtitle', 'Acompanhe o salao, confira o status de e-mail e entre direto na pagina que precisa gerenciar.')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="admin-stat p-3 h-100">
                <div class="admin-stat-label text-muted">Servicos</div>
                <div class="admin-stat-value">{{ $dashboardMetrics['servicos'] }}</div>
                <div class="muted-copy small">catalogo ativo</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="admin-stat p-3 h-100">
                <div class="admin-stat-label text-muted">Profissionais</div>
                <div class="admin-stat-value">{{ $dashboardMetrics['funcionarios'] }}</div>
                <div class="muted-copy small">equipe cadastrada</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="admin-stat p-3 h-100">
                <div class="admin-stat-label text-muted">Agenda de hoje</div>
                <div class="admin-stat-value">{{ $dashboardMetrics['agenda_hoje'] }}</div>
                <div class="muted-copy small">cliente(s) para atender</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="admin-stat p-3 h-100">
                <div class="admin-stat-label text-muted">Faturamento previsto do mes</div>
                <div class="admin-stat-value">R$ {{ number_format($dashboardMetrics['faturamento_previsto_mes'], 2, ',', '.') }}</div>
                <div class="muted-copy small">agenda atual do periodo</div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-5">
            <div class="admin-card p-4 h-100">
                <div class="section-eyebrow text-muted mb-2">Modo atual de e-mail</div>
                <h2 class="h4 fw-bold mb-3">Configuracao de envio</h2>

                @if($mailStatus['ready'])
                    <div class="alert alert-success mb-3">SMTP configurado para envio real de confirmacoes.</div>
                @else
                    <div class="alert alert-warning mb-3">O projeto ainda esta em modo {{ $mailStatus['driver'] }}. Para a Hostinger, configure SMTP para o e-mail de confirmacao chegar na cliente.</div>
                @endif

                <div class="row g-3">
                    <div class="col-12">
                        <div class="border rounded-4 p-3 bg-light-subtle">
                            <div class="small text-muted">Driver</div>
                            <div class="fw-semibold">{{ $mailStatus['driver'] }}</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="border rounded-4 p-3 bg-light-subtle h-100">
                            <div class="small text-muted">Host SMTP</div>
                            <div class="fw-semibold">{{ $mailStatus['smtp_host'] ?: 'Nao configurado' }}</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="border rounded-4 p-3 bg-light-subtle h-100">
                            <div class="small text-muted">Remetente</div>
                            <div class="fw-semibold">{{ $mailStatus['from_address'] ?: 'Nao configurado' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-7">
            <div class="admin-card p-4 h-100">
                <div class="section-eyebrow text-muted mb-2">Atalhos</div>
                <h2 class="h4 fw-bold mb-3">Menu do admin</h2>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <a href="{{ route('admin.agendamentos.index') }}" class="admin-link-card">
                            <div class="admin-link-title">Clientes agendados</div>
                            <div class="admin-link-copy">Veja reservas, confirme horarios e ajuste atendimentos.</div>
                        </a>
                    </div>
                    <div class="col-12 col-md-6">
                        <a href="{{ route('admin.funcionarios.index') }}" class="admin-link-card">
                            <div class="admin-link-title">Profissionais</div>
                            <div class="admin-link-copy">Cadastre novas profissionais e atualize especialidades.</div>
                        </a>
                    </div>
                    <div class="col-12 col-md-6">
                        <a href="{{ route('admin.servicos.index') }}" class="admin-link-card">
                            <div class="admin-link-title">Servicos</div>
                            <div class="admin-link-copy">Gerencie catalogo, valor e duracao dos servicos.</div>
                        </a>
                    </div>
                    <div class="col-12 col-md-6">
                        <a href="{{ route('admin.agendamentos.today') }}" class="admin-link-card">
                            <div class="admin-link-title">Agenda do dia</div>
                            <div class="admin-link-copy">Acompanhe a distribuicao dos atendimentos de hoje.</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-6">
            <div class="admin-card p-4 h-100">
                <div class="section-eyebrow text-muted mb-2">Agenda de hoje</div>
                <h2 class="h4 fw-bold mb-3">Visao rapida</h2>
                @forelse($agendaHojePorProfissional as $profissional => $itens)
                    <div class="border rounded-4 p-3 mb-3 bg-light-subtle">
                        <div class="d-flex justify-content-between gap-3 align-items-start mb-2">
                            <div>
                                <div class="fw-bold">{{ $profissional }}</div>
                                <div class="muted-copy small">{{ $itens->first()?->funcionario?->especialidade ?? 'Especialidade nao informada' }}</div>
                            </div>
                            <span class="soft-chip">{{ $itens->count() }} atendimento(s)</span>
                        </div>
                        @foreach($itens as $agendamento)
                            <div class="py-2 border-top">
                                <div class="fw-semibold">{{ substr($agendamento->horario, 0, 5) }} - {{ $agendamento->user->name ?? 'Cliente' }}</div>
                                <div class="muted-copy small">{{ $agendamento->servicoModel->nome ?? $agendamento->servico }}</div>
                                <div class="muted-copy small">{{ $agendamento->funcionario->especialidade ?? 'Especialidade nao informada' }}</div>
                            </div>
                        @endforeach
                    </div>
                @empty
                    <div class="alert alert-light border mb-0">Nenhum atendimento marcado para hoje.</div>
                @endforelse
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="admin-card p-4 h-100">
                <div class="section-eyebrow text-muted mb-2">Proximos atendimentos</div>
                <h2 class="h4 fw-bold mb-3">O que vem a seguir</h2>
                @forelse($proximosAgendamentos as $agendamento)
                    <div class="border rounded-4 p-3 mb-3 bg-light-subtle">
                        <div class="fw-semibold">{{ $agendamento->user->name ?? 'Cliente' }}</div>
                        <div class="muted-copy small">{{ \Carbon\Carbon::parse($agendamento->data)->format('d/m/Y') }} as {{ substr($agendamento->horario, 0, 5) }}</div>
                        <div class="mt-2">{{ $agendamento->servicoModel->nome ?? $agendamento->servico }}</div>
                        <div class="muted-copy small">{{ $agendamento->funcionario->nome ?? $agendamento->profissional ?? 'Sem profissional' }}</div>
                        <div class="muted-copy small">{{ $agendamento->funcionario->especialidade ?? 'Especialidade nao informada' }}</div>
                    </div>
                @empty
                    <div class="alert alert-light border mb-0">Nenhum proximo atendimento encontrado.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
