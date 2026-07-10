<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda do dia - Studio Franciele Cesario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('partials.pwa-head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #211815;
            --ink-soft: rgba(33, 24, 21, 0.72);
            --copper: #b66c57;
            --panel: rgba(255, 248, 244, 0.94);
            --shadow: 0 24px 55px rgba(22, 15, 14, 0.22);
            --status-pendente: #7b6242;
            --status-pendente-bg: rgba(198, 164, 111, 0.18);
            --status-confirmado: #2f7a57;
            --status-confirmado-bg: rgba(116, 180, 146, 0.18);
            --status-cancelado: #a64d4d;
            --status-cancelado-bg: rgba(208, 113, 113, 0.18);
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
                radial-gradient(circle at top left, rgba(197, 146, 126, 0.44), transparent 38%),
                linear-gradient(150deg, rgba(17, 12, 11, 0.92), rgba(53, 34, 29, 0.76));
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
        .stat-card,
        .agenda-card {
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: var(--shadow);
        }

        .hero-panel {
            background: linear-gradient(135deg, rgba(30, 22, 20, 0.94), rgba(108, 68, 58, 0.8));
            padding: 1.6rem;
            color: #fff7f3;
        }

        .hero-title,
        .section-title {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .hero-title {
            font-size: clamp(2.2rem, 5vw, 3.8rem);
            line-height: 0.95;
            margin: 0.3rem 0 0.8rem;
        }

        .hero-kicker,
        .section-kicker,
        .stat-label {
            text-transform: uppercase;
            letter-spacing: 0.14em;
            font-size: 0.74rem;
            font-weight: 800;
        }

        .hero-copy {
            max-width: 760px;
            color: rgba(255, 247, 243, 0.82);
            margin-bottom: 0;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .hero-actions .btn {
            border-radius: 999px;
            padding-inline: 1rem;
            font-weight: 700;
        }

        .glass-panel,
        .stat-card,
        .agenda-card {
            background: var(--panel);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .glass-panel {
            padding: 1.4rem;
        }

        .stat-card {
            padding: 1rem;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            inset: 0 auto 0 0;
            width: 5px;
            border-radius: 24px 0 0 24px;
            background: rgba(182, 108, 87, 0.5);
        }

        .stat-card--pendente {
            background: linear-gradient(180deg, rgba(255, 247, 228, 0.96), rgba(247, 234, 202, 0.94));
        }

        .stat-card--pendente::after {
            background: var(--status-pendente);
        }

        .stat-card--confirmado {
            background: linear-gradient(180deg, rgba(235, 249, 242, 0.96), rgba(221, 241, 229, 0.94));
        }

        .stat-card--confirmado::after {
            background: var(--status-confirmado);
        }

        .stat-card--cancelado {
            background: linear-gradient(180deg, rgba(255, 240, 240, 0.96), rgba(248, 225, 225, 0.94));
        }

        .stat-card--cancelado::after {
            background: var(--status-cancelado);
        }

        .stat-value {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.25rem;
            line-height: 1;
            margin-top: 0.25rem;
        }

        .section-title {
            font-size: clamp(1.85rem, 4vw, 2.5rem);
            margin-bottom: 0.3rem;
        }

        .section-copy {
            color: var(--ink-soft);
            margin-bottom: 0;
        }

        .agenda-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1rem;
        }

        .agenda-card {
            padding: 1.15rem;
            display: flex;
            flex-direction: column;
            gap: 0.9rem;
            position: relative;
            overflow: hidden;
        }

        .agenda-card::before {
            content: '';
            position: absolute;
            inset: 0 auto 0 0;
            width: 6px;
            background: rgba(182, 108, 87, 0.44);
        }

        .agenda-card--pendente {
            background: linear-gradient(180deg, rgba(255, 249, 238, 0.96), rgba(251, 240, 220, 0.9));
        }

        .agenda-card--pendente::before {
            background: var(--status-pendente);
        }

        .agenda-card--confirmado {
            background: linear-gradient(180deg, rgba(241, 252, 246, 0.96), rgba(229, 245, 235, 0.9));
        }

        .agenda-card--confirmado::before {
            background: var(--status-confirmado);
        }

        .agenda-card--cancelado {
            background: linear-gradient(180deg, rgba(255, 243, 243, 0.96), rgba(247, 228, 228, 0.9));
        }

        .agenda-card--cancelado::before {
            background: var(--status-cancelado);
        }

        .agenda-card.is-cancelado {
            opacity: 0.88;
        }

        .agenda-time {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            width: fit-content;
            padding: 0.45rem 0.7rem;
            border-radius: 999px;
            background: rgba(182, 108, 87, 0.12);
            color: #6b4034;
            font-weight: 800;
        }

        .agenda-client {
            font-size: 1.1rem;
            font-weight: 800;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            border: 1px solid transparent;
        }

        .status-pill--pendente {
            color: var(--status-pendente);
            background: var(--status-pendente-bg);
            border-color: rgba(123, 98, 66, 0.2);
        }

        .status-pill--confirmado {
            color: var(--status-confirmado);
            background: var(--status-confirmado-bg);
            border-color: rgba(47, 122, 87, 0.18);
        }

        .status-pill--cancelado {
            color: var(--status-cancelado);
            background: var(--status-cancelado-bg);
            border-color: rgba(166, 77, 77, 0.18);
        }

        .agenda-meta {
            color: var(--ink-soft);
            margin: 0;
        }

        .agenda-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.65rem;
            margin-top: auto;
        }

        .agenda-actions form {
            flex: 1 1 180px;
        }

        .agenda-actions .btn {
            width: 100%;
            border-radius: 999px;
            font-weight: 700;
        }

        .btn-confirm-action {
            background: linear-gradient(135deg, #2f7a57, #1f6041);
            border: 0;
            color: #fff;
        }

        .btn-confirm-action:hover,
        .btn-confirm-action:focus {
            background: linear-gradient(135deg, #398b64, #245e43);
            color: #fff;
        }

        .btn-cancel-action {
            border: 1px solid rgba(166, 77, 77, 0.3);
            background: rgba(255, 255, 255, 0.7);
            color: var(--status-cancelado);
        }

        .btn-cancel-action:hover,
        .btn-cancel-action:focus {
            background: rgba(166, 77, 77, 0.12);
            color: #8e3d3d;
        }

        @media (max-width: 576px) {
            .hero-actions,
            .agenda-actions {
                flex-direction: column;
            }

            .hero-actions .btn,
            .agenda-actions form,
            .agenda-actions .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <video class="video-bg" autoplay muted loop>
        <source src="/videos/studio2.mp4" type="video/mp4">
        Seu navegador nao suporta video em HTML5.
    </video>
    <div class="overlay"></div>

    <main class="page-wrap">
        <div class="container-xl">
            <section class="hero-panel">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-lg-8">
                        <div class="hero-kicker">Controle administrativo</div>
                        <h1 class="hero-title">Agenda do dia</h1>
                        <p class="hero-copy">Visualize rapidamente tudo o que a administradora vai atender hoje, confirme presencas e cancele horarios sem perder o historico.</p>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="hero-actions justify-content-lg-end">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-light">Voltar ao painel</a>
                            <a href="{{ route('agendamento.create') }}" class="btn btn-outline-light">Novo agendamento</a>
                        </div>
                    </div>
                </div>
            </section>

            @if(session('success'))
                <div class="alert alert-success mt-3 mb-0">{{ session('success') }}</div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning mt-3 mb-0">{{ session('warning') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger mt-3 mb-0">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @unless($statusColumnAvailable)
                <div class="alert alert-warning mt-3 mb-0">Execute as migrations para confirmar ou cancelar horarios sem apagar o historico.</div>
            @endunless

            <section class="mt-3 mt-lg-4">
                <div class="row g-3">
                    <div class="col-6 col-lg-3">
                        <div class="stat-card">
                            <div class="stat-label">Total hoje</div>
                            <div class="stat-value">{{ $agendaHojeResumo['total'] }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="stat-card stat-card--pendente">
                            <div class="stat-label">Pendentes</div>
                            <div class="stat-value">{{ $agendaHojeResumo['pendente'] }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="stat-card stat-card--confirmado">
                            <div class="stat-label">Confirmados</div>
                            <div class="stat-value">{{ $agendaHojeResumo['confirmado'] }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="stat-card stat-card--cancelado">
                            <div class="stat-label">Cancelados</div>
                            <div class="stat-value">{{ $agendaHojeResumo['cancelado'] }}</div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="glass-panel mt-3 mt-lg-4">
                <div class="section-kicker">{{ $today->format('d/m/Y') }}</div>
                <div class="section-title">Atendimentos da administradora</div>
                <p class="section-copy">Confirme os horarios validados e cancele encaixes quando houver necessidade.</p>

                @if($agendaHoje->isEmpty())
                    <div class="alert alert-light mt-3 mb-0">Nenhum agendamento registrado para hoje.</div>
                @else
                    <div class="agenda-grid mt-3">
                        @foreach($agendaHoje as $agendamento)
                            @php
                                $statusAtual = $agendamento->status ?? 'pendente';
                            @endphp
                            <article @class([
                                'agenda-card',
                                'agenda-card--pendente' => $statusAtual === 'pendente',
                                'agenda-card--confirmado' => $statusAtual === 'confirmado',
                                'agenda-card--cancelado' => $statusAtual === 'cancelado',
                                'is-cancelado' => $statusAtual === 'cancelado',
                            ])>
                                <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap">
                                    <div class="agenda-time">{{ substr($agendamento->horario, 0, 5) }}</div>
                                    <span @class([
                                        'status-pill',
                                        'status-pill--pendente' => $statusAtual === 'pendente',
                                        'status-pill--confirmado' => $statusAtual === 'confirmado',
                                        'status-pill--cancelado' => $statusAtual === 'cancelado',
                                    ])>{{ ucfirst($statusAtual) }}</span>
                                </div>

                                <div>
                                    <div class="agenda-client">{{ $agendamento->user->name ?? 'Cliente' }}</div>
                                    <p class="agenda-meta mb-1">{{ $agendamento->servicoModel->nome ?? $agendamento->servico }}</p>
                                    <p class="agenda-meta mb-1">Profissional: {{ $agendamento->funcionario->nome ?? $agendamento->profissional ?? 'Sem profissional' }}</p>
                                    <p class="agenda-meta mb-1">Especialidade: {{ $agendamento->funcionario->especialidade ?? 'Nao informada' }}</p>
                                    @if($agendamento->servicoModel)
                                        <p class="agenda-meta mb-0">R$ {{ number_format($agendamento->servicoModel->valor, 2, ',', '.') }}</p>
                                    @endif
                                </div>

                                @if($statusColumnAvailable)
                                    <div class="agenda-actions">
                                        <form method="POST" action="{{ route('admin.agendamentos.status', $agendamento) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="confirmado">
                                            <button class="btn btn-confirm-action" type="submit" @disabled($statusAtual === 'confirmado')>Confirmar horario</button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.agendamentos.status', $agendamento) }}" onsubmit="return confirm('Cancelar este horario?')">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="cancelado">
                                            <button class="btn btn-cancel-action" type="submit" @disabled($statusAtual === 'cancelado')>Cancelar horario</button>
                                        </form>
                                    </div>
                                @endif
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Sair</button>
            </form>
        </div>
    </main>

    @include('partials.pwa-install')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>