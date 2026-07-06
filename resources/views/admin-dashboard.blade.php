<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h1 class="h3 mb-1">Painel Administrativo</h1>
                            <p class="text-muted mb-0">Visão geral do sistema</p>
                        </div>
                        <a href="{{ route('logout') }}"
                           class="btn btn-outline-dark"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Sair
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="border rounded p-3 bg-white">
                                <div class="text-muted small">Usuário logado</div>
                                <div class="fw-semibold">{{ auth()->user()->name }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3 bg-white">
                                <div class="text-muted small">E-mail</div>
                                <div class="fw-semibold">{{ auth()->user()->email }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3 bg-white">
                                <div class="text-muted small">Perfil</div>
                                <div class="fw-semibold text-capitalize">{{ auth()->user()->role }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 alert alert-success mb-0">
                        Você está autenticado como administrador.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
