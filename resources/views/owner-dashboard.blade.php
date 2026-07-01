<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Studio Franciele Cesario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #211815;
            --ink-soft: rgba(33, 24, 21, 0.72);
            --copper: #b66c57;
            --shadow: 0 24px 55px rgba(22, 15, 14, 0.22);
        }

        body, html {
            min-height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Manrope', sans-serif;
            color: var(--ink);
        }

        .video-bg {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            object-fit: cover;
            z-index: -2;
        }

        .overlay {
            position: fixed;
            inset: 0;
            background:
                radial-gradient(circle at top left, rgba(197, 146, 126, 0.48), transparent 38%),
                linear-gradient(150deg, rgba(17, 12, 11, 0.9), rgba(53, 34, 29, 0.7));
            z-index: -1;
        }

        .page-wrap {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            padding: 28px 12px 40px;
        }

        .hero-panel,
        .glass-panel,
        .stat-card {
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: var(--shadow);
        }

        .hero-panel {
            background: linear-gradient(135deg, rgba(30, 22, 20, 0.94), rgba(108, 68, 58, 0.8));
            padding: 1.6rem;
            color: #fff7f3;
        }

        .hero-kicker,
        .section-kicker,
        .stat-label {
            text-transform: uppercase;
            letter-spacing: 0.14em;
            font-size: 0.74rem;
            font-weight: 800;
        }

        .hero-title,
        .section-title {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .hero-title {
            font-size: clamp(2.25rem, 5vw, 4rem);
            line-height: 0.95;
            margin: 0.4rem 0 0.9rem;
        }

        .hero-copy {
            max-width: 720px;
            color: rgba(255, 247, 243, 0.82);
            margin-bottom: 0;
        }

        .hero-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.65rem;
            margin-top: 1.3rem;
        }

        .hero-badge,
        .mini-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.4rem 0.75rem;
            border-radius: 999px;
            font-size: 0.84rem;
            font-weight: 700;
        }

        .hero-badge {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.14);
            color: #fff7f3;
        }

        .mini-chip {
            background: rgba(100, 116, 101, 0.12);
            color: #425145;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            justify-content: flex-end;
        }

        .hero-actions .btn {
            border-radius: 999px;
            padding-inline: 1rem;
            font-weight: 700;
        }
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Studio Franciele Cesario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #211815;
            --ink-soft: rgba(33, 24, 21, 0.72);
            --copper: #b66c57;
            --shadow: 0 24px 55px rgba(22, 15, 14, 0.22);
        }

        body, html {
            min-height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Manrope', sans-serif;
            color: var(--ink);
        }

        .video-bg {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            object-fit: cover;
            z-index: -2;
        }

        .overlay {
            position: fixed;
            inset: 0;
            background:
                radial-gradient(circle at top left, rgba(197, 146, 126, 0.48), transparent 38%),
                linear-gradient(150deg, rgba(17, 12, 11, 0.9), rgba(53, 34, 29, 0.7));
            z-index: -1;
        }

        .page-wrap {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            padding: 28px 12px 40px;
        }

        .hero-panel,
        .glass-panel,
        .stat-card {
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: var(--shadow);
        }

        .hero-panel {
            background: linear-gradient(135deg, rgba(30, 22, 20, 0.94), rgba(108, 68, 58, 0.8));
            padding: 1.6rem;
            color: #fff7f3;
        }

        .hero-kicker,
        .section-kicker,
        .stat-label {
            text-transform: uppercase;
            letter-spacing: 0.14em;
            font-size: 0.74rem;
            font-weight: 800;
        }

        .hero-title,
        .section-title {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .hero-title {
            font-size: clamp(2.25rem, 5vw, 4rem);
            line-height: 0.95;
            margin: 0.4rem 0 0.9rem;
        }

        .hero-copy {
            max-width: 720px;
            color: rgba(255, 247, 243, 0.82);
            margin-bottom: 0;
        }

        .hero-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.65rem;
            margin-top: 1.3rem;
        }

        .hero-badge,
        .mini-chip {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            font-weight: 700;
        }

        .hero-badge {
            padding: 0.55rem 0.8rem;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.14);
            color: #fff7f3;
            font-size: 0.86rem;
        }

        .mini-chip {
            padding: 0.3rem 0.65rem;
            background: rgba(100, 116, 101, 0.12);
            color: #425145;
            font-size: 0.8rem;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            justify-content: flex-end;
        }

        .hero-actions .btn {
            border-radius: 999px;
            padding-inline: 1rem;
            font-weight: 700;
        }

        .stat-card {
            background: linear-gradient(180deg, rgba(255, 247, 240, 0.98), rgba(244, 232, 224, 0.94));
            padding: 1.2rem;
            height: 100%;
        }

        .stat-label {
            color: rgba(76, 49, 42, 0.62);
            margin-bottom: 0.45rem;
        }

        .stat-value {
            font-size: clamp(1.8rem, 3vw, 2.5rem);
            font-weight: 800;
            line-height: 1;
            color: var(--ink);
        }

        .stat-note,
        .section-copy,
        .timeline-meta,
        .muted-copy,
        .table-note,
        .result-count,
        .lane-count {
            color: var(--ink-soft);
            font-size: 0.92rem;
        }

        .glass-panel {
            background: rgba(250, 245, 240, 0.95);
            padding: 1.35rem;
        }

        .section-kicker {
            color: rgba(104, 62, 51, 0.62);
            margin-bottom: 0.2rem;
        }

        .section-title {
            font-size: 2rem;
            line-height: 0.95;
            margin-bottom: 0.9rem;
        }

        .form-shell,
        .agenda-lane,
        .upcoming-card,
        .filters-shell {
            background: rgba(255, 255, 255, 0.58);
            border: 1px solid rgba(182, 108, 87, 0.14);
            border-radius: 18px;
        }

        .form-shell,
        .agenda-lane,
        .upcoming-card,
        .filters-shell {
            padding: 1rem;
        }

        .form-shell {
            margin-bottom: 1rem;
        }

        .form-control,
        .form-select {
            border-radius: 14px;
            border-color: rgba(182, 108, 87, 0.18);
            background: rgba(255, 255, 255, 0.92);
            color: var(--ink);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: rgba(182, 108, 87, 0.48);
            box-shadow: 0 0 0 0.2rem rgba(182, 108, 87, 0.12);
        }

        .btn-primary-admin {
            background: linear-gradient(135deg, var(--copper), #91513f);
            border: 0;
            color: #fff;
        }

        .btn-primary-admin:hover,
        .btn-primary-admin:focus {
            background: linear-gradient(135deg, #c67a62, #9e5a47);
            color: #fff;
        }

        .btn-outline-admin {
            border: 1px solid rgba(182, 108, 87, 0.28);
            color: var(--ink);
            background: rgba(255, 255, 255, 0.5);
        }

        .btn-outline-admin:hover,
        .btn-outline-admin:focus {
            background: rgba(182, 108, 87, 0.1);
            color: var(--ink);
        }

        .catalog-table,
        .agenda-table {
            --bs-table-bg: transparent;
        }

        .catalog-table thead th,
        .agenda-table thead th {
            font-size: 0.77rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: rgba(93, 58, 48, 0.62);
            border-bottom-color: rgba(93, 58, 48, 0.12);
        }

        .catalog-table tbody td,
        .agenda-table tbody td {
            border-bottom-color: rgba(93, 58, 48, 0.1);
            vertical-align: top;
        }

        .inline-form {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .inline-form input,
        .inline-form select {
            min-width: 110px;
        }

        .lane-header,
        .result-toolbar {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 0.8rem;
        }

        .timeline-item {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 0.9rem;
            padding: 0.8rem 0;
            border-top: 1px solid rgba(93, 58, 48, 0.08);
        }

        .timeline-item:first-child {
            border-top: 0;
            padding-top: 0;
        }

        .timeline-hour {
            min-width: 56px;
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--copper);
        }

        .timeline-client,
        .lane-title,
        .upcoming-title {
            font-weight: 800;
            margin: 0;
        }

        .upcoming-list {
            display: grid;
            gap: 0.8rem;
        }

        .upcoming-date {
            font-size: 0.77rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: rgba(93, 58, 48, 0.58);
        }

        .agenda-actions {
            display: grid;
            gap: 0.65rem;
            min-width: 320px;
        }

        .agenda-edit-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.5rem;
        }

        .agenda-edit-grid .full {
            grid-column: 1 / -1;
        }

        .pagination {
            --bs-pagination-border-radius: 999px;
            --bs-pagination-color: var(--ink);
            --bs-pagination-hover-color: var(--ink);
            --bs-pagination-active-bg: var(--copper);
            --bs-pagination-active-border-color: var(--copper);
            --bs-pagination-focus-box-shadow: 0 0 0 0.2rem rgba(182, 108, 87, 0.12);
        }

        @media (max-width: 992px) {
            .agenda-actions {
                min-width: 0;
            }
        }

        @media (max-width: 768px) {
            .page-wrap {
                padding: 14px 8px 24px;
            }

            .hero-panel,
            .glass-panel,
            .stat-card {
                border-radius: 20px;
            }

            .hero-panel {
                padding: 1.1rem;
            }

            .section-title {
                font-size: 1.55rem;
            }

            .hero-title {
                font-size: 2rem;
            }

            .hero-meta,
            .hero-actions {
                width: 100%;
            }

            .hero-actions {
                justify-content: flex-start;
            }

            .hero-actions .btn {
                width: 100%;
            }

            .col-6.col-xl-2,
            .col-6.col-xl-3 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .stat-card {
                padding: 1rem;
            }

            .stat-value {
                font-size: 1.7rem;
            }

            .form-shell,
            .agenda-lane,
            .upcoming-card,
            .filters-shell {
                padding: 0.85rem;
            }

            .form-shell .col-12,
            .form-shell .col-12.col-md-7.col-xl-12,
            .form-shell .col-12.col-md-5.col-xl-12 {
                width: 100%;
            }

            .table-responsive {
                margin-right: -0.25rem;
                margin-left: -0.25rem;
            }

            .table-responsive table {
                min-width: 760px;
            }

            .catalog-table,
            .agenda-table {
                font-size: 0.85rem;
            }

            .catalog-table thead th,
            .agenda-table thead th {
                white-space: nowrap;
            }

            .agenda-actions {
                width: 100%;
            }

            .inline-form,
            .agenda-edit-grid {
                display: flex;
                flex-direction: column;
                width: 100%;
            }

            .inline-form .btn,
            .agenda-edit-grid .btn {
                width: 100%;
            }

            .inline-form input,
            .inline-form select,
            .agenda-edit-grid input,
            .agenda-edit-grid select {
                min-width: 0;
                width: 100%;
            }

            .agenda-edit-grid {
                grid-template-columns: 1fr;
            }

            .agenda-edit-grid .full {
                grid-column: auto;
            }

            .timeline-item {
                grid-template-columns: 1fr;
                gap: 0.4rem;
            }

            .timeline-hour,
            .mini-chip {
                width: fit-content;
            }
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
        <section class="hero-panel mb-3 mb-lg-4">
            <div class="row g-4 align-items-end">
                <div class="col-12 col-lg-8">
                    <div class="hero-kicker">Painel operacional</div>
                    <h1 class="hero-title">Studio Franciele Cesario</h1>
                    <p class="hero-copy">Controle servicos, equipe e agenda do estudio em uma unica visao. Agora o Studio Franciele Cesario tambem consegue remanejar ou cancelar reservas sem sair da tela administrativa.</p>
                    <div class="hero-meta">
                        <span class="hero-badge">Hoje {{ now()->format('d/m/Y') }}</span>
                        <span class="hero-badge">{{ $dashboardMetrics['agenda_hoje'] }} atendimentos na agenda do dia</span>
                        <span class="hero-badge">{{ $dashboardMetrics['clientes_mes'] }} clientes no mes</span>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="hero-actions">
                        <a href="{{ route('agendamento.create') }}" class="btn btn-light">Abrir agenda</a>
                        <a href="{{ route('agendamento.meus') }}" class="btn btn-outline-light">Ver meus agendamentos</a>
                    </div>
                </div>
            </div>
        </section>

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

        <section class="row g-3 mb-3 mb-lg-4">
            <div class="col-6 col-xl-2">
                <div class="stat-card">
                    <div class="stat-label">Servicos</div>
                    <div class="stat-value">{{ $dashboardMetrics['servicos'] }}</div>
                    <div class="stat-note">catalogo ativo no sistema</div>
                </div>
            </div>
            <div class="col-6 col-xl-2">
                <div class="stat-card">
                    <div class="stat-label">Equipe</div>
                    <div class="stat-value">{{ $dashboardMetrics['funcionarios'] }}</div>
                    <div class="stat-note">profissionais disponiveis</div>
                </div>
            </div>
            <div class="col-6 col-xl-2">
                <div class="stat-card">
                    <div class="stat-label">Hoje</div>
                    <div class="stat-value">{{ $dashboardMetrics['agenda_hoje'] }}</div>
                    <div class="stat-note">atendimentos marcados</div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-label">Faturamento previsto do mes</div>
                    <div class="stat-value">R$ {{ number_format($dashboardMetrics['faturamento_previsto_mes'], 2, ',', '.') }}</div>
                    <div class="stat-note">soma dos servicos vinculados aos agendamentos</div>
                </div>
            </div>
            <div class="col-12 col-xl-3">
                <div class="stat-card">
                    <div class="stat-label">Ticket medio do mes</div>
                    <div class="stat-value">R$ {{ number_format($dashboardMetrics['ticket_medio_mes'], 2, ',', '.') }}</div>
                    <div class="stat-note">valor medio por atendimento no periodo atual</div>
                </div>
            </div>
        </section>

        <section class="row g-3">
            <div class="col-12 col-xl-4">
                <div class="glass-panel h-100">
                    <div class="section-kicker">Gestao de catalogo</div>
                    <div class="section-title">Servicos e precos</div>
                    <p class="section-copy">Cadastre, ajuste ou retire servicos sem sair do painel.</p>

                    <div class="form-shell">
                        <form method="POST" action="{{ route('owner.servicos.store') }}" class="row g-2">
                            @csrf
                            <div class="col-12 col-md-7 col-xl-12">
                                <input type="text" class="form-control" name="nome" placeholder="Nome do servico" value="{{ old('nome') }}" required>
                            </div>
                            <div class="col-12 col-md-5 col-xl-12">
                                <input type="number" class="form-control" name="valor" min="0" step="0.01" placeholder="Valor" value="{{ old('valor') }}" required>
                            </div>
                            <div class="col-12 col-md-5 col-xl-12">
                                <label class="form-label mb-1" for="duracao-novo-servico">Duracao</label>
                                <input type="time" class="form-control" id="duracao-novo-servico" name="duracao" step="60" value="{{ old('duracao') }}">
                                <small class="text-muted d-block mt-1">Informe quanto tempo este servico ocupa na agenda.</small>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary-admin w-100">Salvar servico</button>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle catalog-table mb-0">
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
                                            <form method="POST" action="{{ route('owner.servicos.update', $servico) }}" class="inline-form mb-2 justify-content-end">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" class="form-control form-control-sm" name="nome" value="{{ $servico->nome }}" required>
                                                <input type="number" class="form-control form-control-sm" name="valor" min="0" step="0.01" value="{{ $servico->valor }}" required>
                                                <input type="time" class="form-control form-control-sm" name="duracao" step="60" value="{{ $servico->duracao ? substr($servico->duracao, 0, 5) : '' }}">
                                                <button class="btn btn-sm btn-outline-admin" type="submit">Atualizar</button>
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
                                        <td colspan="4" class="table-note">Nenhum servico cadastrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="glass-panel h-100">
                    <div class="section-kicker">Gestao da equipe</div>
                    <div class="section-title">Profissionais</div>
                    <p class="section-copy">Mantenha a equipe disponivel para o cliente com os nomes corretos.</p>

                    <div class="form-shell">
                        <form method="POST" action="{{ route('owner.funcionarios.store') }}" class="row g-2">
                            @csrf
                            <div class="col-12">
                                <input type="text" class="form-control" name="nome" placeholder="Nome da profissional" value="{{ old('nome') }}" required>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary-admin w-100">Salvar profissional</button>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle catalog-table mb-0">
                            <thead>
                                <tr>
                                    <th>Profissional</th>
                                    <th class="text-end">Acao</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($funcionarios as $funcionario)
                                    <tr>
                                        <td><strong>{{ $funcionario->nome }}</strong></td>
                                        <td class="text-end">
                                            <form method="POST" action="{{ route('owner.funcionarios.update', $funcionario) }}" class="inline-form mb-2 justify-content-end">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" class="form-control form-control-sm" name="nome" value="{{ $funcionario->nome }}" required>
                                                <button class="btn btn-sm btn-outline-admin" type="submit">Atualizar</button>
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
                                        <td colspan="2" class="table-note">Nenhuma profissional cadastrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="glass-panel h-100">
                    <div class="section-kicker">Visao de fluxo</div>
                    <div class="section-title">Proximos atendimentos</div>
                    <p class="section-copy">Bata o olho no que vem agora e identifique janelas ociosas antes de abrir outra tela.</p>

                    <div class="upcoming-list">
                        @forelse($proximosAgendamentos as $agendamento)
                            <div class="upcoming-card">
                                <div class="upcoming-date">{{ \Carbon\Carbon::parse($agendamento->data)->format('d/m/Y') }} as {{ substr($agendamento->horario, 0, 5) }}</div>
                                <div class="upcoming-title">{{ $agendamento->user->name ?? 'Cliente' }}</div>
                                <div class="muted-copy">{{ $agendamento->servicoModel->nome ?? $agendamento->servico }}</div>
                                <div class="muted-copy">{{ $agendamento->funcionario->nome ?? $agendamento->profissional ?? 'Profissional nao informada' }}</div>
                            </div>
                        @empty
                            <div class="upcoming-card">
                                <div class="upcoming-title">Sem proximos atendimentos</div>
                                <div class="muted-copy">A agenda futura esta vazia neste momento.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        <section class="glass-panel mt-3 mt-lg-4">
            <div class="row g-3 align-items-start">
                <div class="col-12 col-lg-4">
                    <div class="section-kicker">Operacao do dia</div>
                    <div class="section-title">Agenda de hoje</div>
                    <p class="section-copy">Veja a distribuicao dos atendimentos por profissional para decidir encaixes, pausas e prioridade.</p>
                </div>
                <div class="col-12 col-lg-8">
                    <div class="row g-3">
                        @forelse($agendaHojePorProfissional as $profissional => $itens)
                            <div class="col-12 col-xl-6">
                                <div class="agenda-lane h-100">
                                    <div class="lane-header mb-2">
                                        <h3 class="lane-title">{{ $profissional }}</h3>
                                        <span class="lane-count">{{ $itens->count() }} atendimento(s)</span>
                                    </div>

                                    @foreach($itens as $agendamento)
                                        <div class="timeline-item">
                                            <div class="timeline-hour">{{ substr($agendamento->horario, 0, 5) }}</div>
                                            <div>
                                                <div class="timeline-client">{{ $agendamento->user->name ?? 'Cliente' }}</div>
                                                <div class="timeline-meta">{{ $agendamento->servicoModel->nome ?? $agendamento->servico }}</div>
                                                @if($agendamento->servicoModel)
                                                    <div class="timeline-meta">R$ {{ number_format($agendamento->servicoModel->valor, 2, ',', '.') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="agenda-lane">
                                    <div class="timeline-client">Nenhum atendimento hoje.</div>
                                    <div class="timeline-meta">A agenda do dia esta livre. Este e um bom momento para revisar servicos e disponibilidade.</div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        <section class="glass-panel mt-3 mt-lg-4">
            <div class="section-kicker">Consulta administrativa</div>
            <div class="section-title">Agendamentos gerais</div>
            <p class="section-copy">Filtre, remaneje e cancele reservas diretamente da tabela administrativa.</p>

            <div class="filters-shell mb-3">
                <form method="GET" action="{{ route('owner.dashboard') }}" class="row g-2">
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
                        <button class="btn btn-primary-admin" type="submit">Aplicar filtros</button>
                        <a href="{{ route('owner.dashboard') }}" class="btn btn-outline-admin">Limpar busca</a>
                    </div>
                </form>
            </div>

            <div class="result-toolbar mb-3">
                <span class="mini-chip">{{ $agendamentos->total() }} resultado(s)</span>
                <span class="result-count">Exibindo {{ $agendamentos->count() }} itens nesta pagina</span>
            </div>

            <div class="table-responsive">
                <table class="table table-sm agenda-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Horario</th>
                            <th>Cliente</th>
                            <th>Servico</th>
                            <th>Profissional</th>
                            <th class="text-end">Acao</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($agendamentos as $agendamento)
                            @php
                                $servicoSelecionado = $agendamento->servico_id ?? optional($servicos->firstWhere('nome', $agendamento->servico))->id;
                                $funcionarioSelecionado = $agendamento->funcionario_id ?? optional($funcionarios->firstWhere('nome', $agendamento->profissional))->id;
                            @endphp
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($agendamento->data)->format('d/m/Y') }}</td>
                                <td><span class="mini-chip">{{ substr($agendamento->horario, 0, 5) }}</span></td>
                                <td><strong>{{ $agendamento->user->name ?? 'Cliente' }}</strong></td>
                                <td>
                                    <strong>{{ $agendamento->servicoModel->nome ?? $agendamento->servico }}</strong>
                                    @if($agendamento->servicoModel)
                                        <div class="muted-copy">R$ {{ number_format($agendamento->servicoModel->valor, 2, ',', '.') }}</div>
                                    @endif
                                </td>
                                <td>{{ $agendamento->funcionario->nome ?? $agendamento->profissional ?? 'Sem profissional' }}</td>
                                <td class="text-end">
                                    <div class="agenda-actions ms-auto">
                                        <form method="POST" action="{{ route('owner.agendamentos.update', $agendamento) }}" class="agenda-edit-grid">
                                            @csrf
                                            @method('PUT')
                                            <input type="date" class="form-control form-control-sm" name="data" value="{{ \Carbon\Carbon::parse($agendamento->data)->format('Y-m-d') }}" required>
                                            <input type="time" class="form-control form-control-sm" name="horario" value="{{ substr($agendamento->horario, 0, 5) }}" required>
                                            <select name="servico_id" class="form-select form-select-sm full" required>
                                                <option value="">Selecione o servico</option>
                                                @foreach($servicos as $servico)
                                                    <option value="{{ $servico->id }}" @selected((string) $servicoSelecionado === (string) $servico->id)>
                                                        {{ $servico->nome }} - R$ {{ number_format($servico->valor, 2, ',', '.') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <select name="funcionario_id" class="form-select form-select-sm full" required>
                                                <option value="">Selecione a profissional</option>
                                                @foreach($funcionarios as $funcionario)
                                                    <option value="{{ $funcionario->id }}" @selected((string) $funcionarioSelecionado === (string) $funcionario->id)>
                                                        {{ $funcionario->nome }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-sm btn-primary-admin full" type="submit">Salvar ajuste</button>
                                        </form>

                                        <form method="POST" action="{{ route('owner.agendamentos.destroy', $agendamento) }}" onsubmit="return confirm('Cancelar este agendamento?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger w-100" type="submit">Cancelar agendamento</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="table-note">Nenhum agendamento encontrado para esse filtro.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $agendamentos->links() }}
            </div>
        </section>

        <form method="POST" action="/logout" class="mt-3 mt-lg-4">
            @csrf
            <button type="submit" class="btn btn-outline-light">Sair</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
