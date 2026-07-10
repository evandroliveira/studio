<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title', 'Area administrativa') - Studio Franciele Cesario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('partials.pwa-head')
    <style>
        body {
            background: linear-gradient(180deg, #f8f1eb 0%, #f1e6dd 100%);
            color: #2d1c17;
        }

        .admin-shell {
            max-width: 1200px;
        }

        .admin-header,
        .admin-card,
        .admin-nav,
        .admin-stat {
            border: 0;
            border-radius: 24px;
            box-shadow: 0 18px 42px rgba(63, 40, 33, 0.08);
        }

        .admin-header {
            background: linear-gradient(135deg, #2e1f1a, #8f5b4a);
            color: #fff7f3;
        }

        .admin-header .eyebrow,
        .section-eyebrow,
        .admin-stat-label {
            text-transform: uppercase;
            letter-spacing: 0.12em;
            font-size: 0.74rem;
            font-weight: 700;
        }

        .admin-nav {
            background: rgba(255, 255, 255, 0.78);
        }

        .admin-nav .btn {
            border-radius: 999px;
            font-weight: 700;
        }

        .admin-card,
        .admin-stat {
            background: rgba(255, 255, 255, 0.92);
        }

        .admin-stat-value {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
        }

        .admin-card .table th {
            font-size: 0.77rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: rgba(45, 28, 23, 0.56);
        }

        .admin-link-card {
            display: block;
            padding: 1rem;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.9);
            color: #2d1c17;
            text-decoration: none;
            border: 1px solid rgba(143, 91, 74, 0.12);
            height: 100%;
        }

        .admin-link-card:hover,
        .admin-link-card:focus {
            border-color: rgba(143, 91, 74, 0.32);
            color: #2d1c17;
        }

        .admin-link-title {
            font-size: 1rem;
            font-weight: 800;
            margin-bottom: 0.3rem;
        }

        .admin-link-copy,
        .muted-copy {
            color: rgba(45, 28, 23, 0.68);
        }

        .soft-chip,
        .status-chip {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 0.3rem 0.65rem;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .soft-chip {
            background: rgba(117, 135, 103, 0.12);
            color: #445140;
        }

        .status-chip--pendente {
            background: rgba(198, 164, 111, 0.2);
            color: #785f3e;
        }

        .status-chip--confirmado {
            background: rgba(116, 180, 146, 0.2);
            color: #2f7a57;
        }

        .status-chip--cancelado {
            background: rgba(208, 113, 113, 0.2);
            color: #9a4141;
        }

        .table-actions {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            align-items: flex-end;
        }

        .table-actions form {
            width: 100%;
        }

        .table-actions .btn,
        .table-actions .form-control,
        .table-actions .form-select {
            width: 100%;
        }

        @media (min-width: 992px) {
            .table-actions {
                min-width: 280px;
            }
        }
    </style>
</head>
<body>
    <div class="container admin-shell py-4 py-lg-5">
        <div class="admin-header p-4 p-lg-5 mb-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-end">
                <div>
                    <div class="eyebrow mb-2">Area administrativa</div>
                    <h1 class="display-6 fw-bold mb-2">@yield('title')</h1>
                    @hasSection('subtitle')
                        <p class="mb-0 text-white-50">@yield('subtitle')</p>
                    @endif
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-light fw-semibold">Sair</button>
                </form>
            </div>
        </div>

        @include('partials.admin-nav', ['activePage' => $activePage ?? null])

        @if(session('success'))
            <div class="alert alert-success mt-4">{{ session('success') }}</div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning mt-4">{{ session('warning') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mt-4">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mt-4">
            @yield('content')
        </div>
    </div>

    @include('partials.pwa-install')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>