<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area da Dona - Studio Franciele Cesario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html { height: 100%; margin: 0; padding: 0; }
        .video-bg { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; object-fit: cover; z-index: -1; }
        .overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.55); z-index: 0; }
        .page-wrap { position: relative; z-index: 1; min-height: 100vh; padding: 24px 12px; }
        .card { border-radius: 14px; border: 0; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .section-title { font-size: 1.15rem; font-weight: 700; }
        .inline-form { display: flex; gap: 8px; align-items: center; }
        .inline-form input { min-width: 110px; }
        @media (max-width: 768px) {
            .inline-form { flex-direction: column; align-items: stretch; }
            .inline-form .btn { width: 100%; }
        }
    </style>
</head>
<body>
    <video class="video-bg" autoplay muted loop>
        <source src="/videos/studio2.mp4" type="video/mp4">
        Seu navegador não suporta vídeo em HTML5.
    </video>
    <div class="overlay"></div>

    <div class="container page-wrap">
        <div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
            <h2 class="text-white m-0">Area da dona do salao</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('agendamento.create') }}" class="btn btn-light">Agendamentos</a>
                <a href="{{ route('agendamento.meus') }}" class="btn btn-outline-light">Meus agendamentos</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-3">
            <div class="col-12 col-lg-6">
                <div class="card p-3 h-100">
                    <div class="section-title mb-3">Cadastrar servico e valor</div>
                    <form method="POST" action="{{ route('owner.servicos.store') }}" class="row g-2 mb-3">
                        @csrf
                        <div class="col-12 col-md-7">
                            <input type="text" class="form-control" name="nome" placeholder="Nome do servico" required>
                        </div>
                        <div class="col-12 col-md-5">
                            <input type="number" class="form-control" name="valor" min="0" step="0.01" placeholder="Valor" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-dark w-100">Salvar servico</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Servico</th>
                                    <th>Valor</th>
                                    <th class="text-end">Acao</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($servicos as $servico)
                                    <tr>
                                        <td>{{ $servico->nome }}</td>
                                        <td>R$ {{ number_format($servico->valor, 2, ',', '.') }}</td>
                                        <td class="text-end">
                                            <form method="POST" action="{{ route('owner.servicos.update', $servico) }}" class="inline-form mb-1">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" class="form-control form-control-sm" name="nome" value="{{ $servico->nome }}" required>
                                                <input type="number" class="form-control form-control-sm" name="valor" min="0" step="0.01" value="{{ $servico->valor }}" required>
                                                <button class="btn btn-sm btn-outline-primary" type="submit">Editar</button>
                                            </form>
                                            <form method="POST" action="{{ route('owner.servicos.destroy', $servico) }}" onsubmit="return confirm('Excluir este servico?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" type="submit">Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-muted">Nenhum servico cadastrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card p-3 h-100">
                    <div class="section-title mb-3">Cadastrar profissionais</div>
                    <form method="POST" action="{{ route('owner.funcionarios.store') }}" class="row g-2 mb-3">
                        @csrf
                        <div class="col-12 col-md-8">
                            <input type="text" class="form-control" name="nome" placeholder="Nome da profissional" required>
                        </div>
                        <div class="col-12 col-md-4">
                            <button type="submit" class="btn btn-dark w-100">Salvar</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Profissional</th>
                                    <th class="text-end">Acao</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($funcionarios as $funcionario)
                                    <tr>
                                        <td>{{ $funcionario->nome }}</td>
                                        <td class="text-end">
                                            <form method="POST" action="{{ route('owner.funcionarios.update', $funcionario) }}" class="inline-form mb-1">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" class="form-control form-control-sm" name="nome" value="{{ $funcionario->nome }}" required>
                                                <button class="btn btn-sm btn-outline-primary" type="submit">Editar</button>
                                            </form>
                                            <form method="POST" action="{{ route('owner.funcionarios.destroy', $funcionario) }}" onsubmit="return confirm('Excluir este profissional?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" type="submit">Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-muted">Nenhum profissional cadastrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card p-3 mt-3">
            <div class="section-title mb-3">Agendamentos gerais</div>
            <form method="GET" action="{{ route('owner.dashboard') }}" class="row g-2 mb-3">
                <div class="col-12 col-md-4">
                    <label class="form-label">Filtrar por data</label>
                    <input type="date" name="data" class="form-control" value="{{ request('data') }}">
                </div>
                <div class="col-12 col-md-5">
                    <label class="form-label">Filtrar por profissional</label>
                    <select name="funcionario_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($funcionarios as $funcionario)
                            <option value="{{ $funcionario->id }}" @selected((string) request('funcionario_id') === (string) $funcionario->id)>{{ $funcionario->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3 d-flex align-items-end gap-2">
                    <button class="btn btn-dark w-100" type="submit">Filtrar</button>
                    <a href="{{ route('owner.dashboard') }}" class="btn btn-outline-secondary w-100">Limpar</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-sm table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Horario</th>
                            <th>Cliente</th>
                            <th>Servico</th>
                            <th>Profissional</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($agendamentos as $agendamento)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($agendamento->data)->format('d/m/Y') }}</td>
                                <td>{{ substr($agendamento->horario, 0, 5) }}</td>
                                <td>{{ $agendamento->user->name ?? 'Cliente' }}</td>
                                <td>
                                    {{ $agendamento->servicoModel->nome ?? $agendamento->servico }}
                                    @if($agendamento->servicoModel)
                                        <small class="text-muted d-block">R$ {{ number_format($agendamento->servicoModel->valor, 2, ',', '.') }}</small>
                                    @endif
                                </td>
                                <td>{{ $agendamento->funcionario->nome ?? $agendamento->profissional }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-muted">Nenhum agendamento encontrado para esse filtro.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $agendamentos->links() }}
            </div>
        </div>

        <form method="POST" action="/logout" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-outline-light">Sair</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
