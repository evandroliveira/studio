@extends('layouts.admin')

@php
    $activePage = 'agendamentos';
@endphp

@section('page_title', 'Clientes agendados')
@section('title', 'Clientes agendados')
@section('subtitle', 'Consulte reservas, confirme horarios, ajuste atendimentos e acompanhe a agenda do estudio em um lugar so.')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="admin-stat p-3 h-100">
                <div class="admin-stat-label text-muted">Hoje</div>
                <div class="admin-stat-value">{{ $dashboardMetrics['agenda_hoje'] }}</div>
                <div class="muted-copy small">atendimento(s) agendado(s)</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="admin-stat p-3 h-100">
                <div class="admin-stat-label text-muted">Clientes do mes</div>
                <div class="admin-stat-value">{{ $dashboardMetrics['clientes_mes'] }}</div>
                <div class="muted-copy small">clientes unicos no periodo</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="admin-stat p-3 h-100">
                <div class="admin-stat-label text-muted">Faturamento previsto</div>
                <div class="admin-stat-value">R$ {{ number_format($dashboardMetrics['faturamento_previsto_mes'], 2, ',', '.') }}</div>
                <div class="muted-copy small">agenda vinculada a servicos</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="admin-stat p-3 h-100">
                <div class="admin-stat-label text-muted">Ticket medio</div>
                <div class="admin-stat-value">R$ {{ number_format($dashboardMetrics['ticket_medio_mes'], 2, ',', '.') }}</div>
                <div class="muted-copy small">media por atendimento</div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-6">
            <div class="admin-card p-4 h-100">
                <div class="section-eyebrow text-muted mb-2">Agenda de hoje</div>
                <h2 class="h4 fw-bold mb-3">Visao por profissional</h2>
                @forelse($agendaHojePorProfissional as $profissional => $itens)
                    <div class="border rounded-4 p-3 mb-3 bg-light-subtle">
                        <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                            <div>
                                <div class="fw-bold">{{ $profissional }}</div>
                                <div class="muted-copy small">{{ $itens->first()?->funcionario?->especialidade ?? 'Especialidade nao informada' }}</div>
                            </div>
                            <span class="soft-chip">{{ $itens->count() }} atendimento(s)</span>
                        </div>

                        @foreach($itens as $agendamento)
                            @php
                                $statusAtual = $agendamento->status ?? 'pendente';
                            @endphp
                            <div class="d-flex justify-content-between align-items-start gap-3 py-2 border-top">
                                <div>
                                    <div class="fw-semibold">{{ substr($agendamento->horario, 0, 5) }} - {{ $agendamento->user->name ?? 'Cliente' }}</div>
                                    <div class="muted-copy small">{{ $agendamento->servicoModel->nome ?? $agendamento->servico }}</div>
                                </div>
                                <span class="status-chip status-chip--{{ $statusAtual }}">{{ ucfirst($statusAtual) }}</span>
                            </div>
                        @endforeach
                    </div>
                @empty
                    <div class="alert alert-light border mb-0">Nenhum atendimento registrado para hoje.</div>
                @endforelse
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="admin-card p-4 h-100">
                <div class="section-eyebrow text-muted mb-2">Proximos passos</div>
                <h2 class="h4 fw-bold mb-3">Proximos atendimentos</h2>
                @forelse($proximosAgendamentos as $agendamento)
                    <div class="border rounded-4 p-3 mb-3 bg-light-subtle">
                        <div class="fw-semibold">{{ $agendamento->user->name ?? 'Cliente' }}</div>
                        <div class="muted-copy small">{{ \Carbon\Carbon::parse($agendamento->data)->format('d/m/Y') }} as {{ substr($agendamento->horario, 0, 5) }}</div>
                        <div class="mt-2">{{ $agendamento->servicoModel->nome ?? $agendamento->servico }}</div>
                        <div class="muted-copy small">{{ $agendamento->funcionario->nome ?? $agendamento->profissional ?? 'Sem profissional' }} - {{ $agendamento->funcionario->especialidade ?? 'Especialidade nao informada' }}</div>
                    </div>
                @empty
                    <div class="alert alert-light border mb-0">Nenhum proximo atendimento encontrado no momento.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="admin-card p-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-3">
            <div>
                <div class="section-eyebrow text-muted mb-2">Consulta administrativa</div>
                <h2 class="h4 fw-bold mb-1">Tabela de clientes agendados</h2>
                <p class="muted-copy mb-0">Filtre por cliente, data ou profissional para localizar e ajustar reservas rapidamente.</p>
            </div>
            <div class="d-flex flex-wrap gap-2 align-items-start">
                <a href="{{ route('admin.agendamentos.today') }}" class="btn btn-dark">Agenda do dia</a>
                <a href="{{ route('agendamento.create') }}" class="btn btn-outline-dark">Novo agendamento</a>
            </div>
        </div>

        @unless($statusColumnAvailable)
            <div class="alert alert-warning">Execute as migrations para confirmar ou cancelar horarios sem apagar o historico.</div>
        @endunless

        <form method="GET" action="{{ route('admin.agendamentos.index') }}" class="row g-2 mb-3">
            <div class="col-12 col-md-4">
                <label class="form-label">Cliente</label>
                <input type="text" name="cliente" class="form-control" value="{{ request('cliente') }}" placeholder="Nome da cliente">
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label">Data</label>
                <input type="date" name="data" class="form-control" value="{{ request('data') }}">
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label">Profissional</label>
                <select name="funcionario_id" class="form-select">
                    <option value="">Todas</option>
                    @foreach($funcionarios as $funcionario)
                        <option value="{{ $funcionario->id }}" @selected((string) request('funcionario_id') === (string) $funcionario->id)>{{ $funcionario->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 d-flex flex-wrap gap-2">
                <button class="btn btn-dark" type="submit">Aplicar filtros</button>
                <a href="{{ route('admin.agendamentos.index') }}" class="btn btn-outline-dark">Limpar busca</a>
            </div>
        </form>

        <div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
            <span class="soft-chip">{{ $agendamentos->total() }} resultado(s)</span>
            <span class="muted-copy small">Exibindo {{ $agendamentos->count() }} item(ns) nesta pagina</span>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Horario</th>
                        <th>Cliente</th>
                        <th>Servico</th>
                        <th>Profissional</th>
                        <th>Status</th>
                        <th class="text-end">Acao</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agendamentos as $agendamento)
                        @php
                            $servicoSelecionado = $agendamento->servico_id ?? optional($servicos->firstWhere('nome', $agendamento->servico))->id;
                            $funcionarioSelecionado = $agendamento->funcionario_id ?? optional($funcionarios->firstWhere('nome', $agendamento->profissional))->id;
                            $statusAtual = $agendamento->status ?? 'pendente';
                        @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($agendamento->data)->format('d/m/Y') }}</td>
                            <td><span class="soft-chip">{{ substr($agendamento->horario, 0, 5) }}</span></td>
                            <td><strong>{{ $agendamento->user->name ?? 'Cliente' }}</strong></td>
                            <td>
                                <div class="fw-semibold">{{ $agendamento->servicoModel->nome ?? $agendamento->servico }}</div>
                                @if($agendamento->servicoModel)
                                    <div class="muted-copy small">R$ {{ number_format($agendamento->servicoModel->valor, 2, ',', '.') }}</div>
                                @endif
                            </td>
                            <td>
                                <div>{{ $agendamento->funcionario->nome ?? $agendamento->profissional ?? 'Sem profissional' }}</div>
                                <div class="muted-copy small">{{ $agendamento->funcionario->especialidade ?? 'Especialidade nao informada' }}</div>
                            </td>
                            <td><span class="status-chip status-chip--{{ $statusAtual }}">{{ ucfirst($statusAtual) }}</span></td>
                            <td class="text-end">
                                <div class="table-actions ms-auto">
                                    @if($statusColumnAvailable)
                                        <form method="POST" action="{{ route('admin.agendamentos.status', $agendamento) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="confirmado">
                                            <button class="btn btn-sm btn-outline-success" type="submit" @disabled($statusAtual === 'confirmado')>Confirmar horario</button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.agendamentos.status', $agendamento) }}" onsubmit="return confirm('Cancelar este horario?')">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="cancelado">
                                            <button class="btn btn-sm btn-outline-danger" type="submit" @disabled($statusAtual === 'cancelado')>Cancelar horario</button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.agendamentos.update', $agendamento) }}" class="d-grid gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="date" class="form-control form-control-sm" name="data" value="{{ \Carbon\Carbon::parse($agendamento->data)->format('Y-m-d') }}" required>
                                        <input type="time" class="form-control form-control-sm" name="horario" value="{{ substr($agendamento->horario, 0, 5) }}" required>
                                        <select name="servico_id" class="form-select form-select-sm" required>
                                            <option value="">Selecione o servico</option>
                                            @foreach($servicos as $servico)
                                                <option value="{{ $servico->id }}" @selected((string) $servicoSelecionado === (string) $servico->id)>
                                                    {{ $servico->nome }} - R$ {{ number_format($servico->valor, 2, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <select name="funcionario_id" class="form-select form-select-sm" required>
                                            <option value="">Selecione a profissional</option>
                                            @foreach($funcionarios as $funcionario)
                                                <option value="{{ $funcionario->id }}" @selected((string) $funcionarioSelecionado === (string) $funcionario->id)>
                                                    {{ $funcionario->nome }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-sm btn-dark" type="submit">Salvar ajuste</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="muted-copy">Nenhum agendamento encontrado para esse filtro.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $agendamentos->links() }}
        </div>
    </div>
@endsection