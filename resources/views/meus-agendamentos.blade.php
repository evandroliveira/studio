<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Agendamentos - Studio Franciele Cesario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html { height: 100%; margin: 0; padding: 0; }
        .video-bg { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; object-fit: cover; z-index: -1; }
        .overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.5); z-index: 0; }
        .container { position: relative; z-index: 1; margin-top: 60px; }
        .card { background: rgba(255,255,255,0.97); border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.2); }
    </style>
</head>
<body>
    <video class="video-bg" autoplay muted loop>
        <source src="/videos/studio2.mp4" type="video/mp4">
        Seu navegador não suporta vídeo em HTML5.
    </video>
    <div class="overlay"></div>
    <div class="container">
        <div class="card p-4 mx-auto" style="max-width:600px;">
            <h3 class="mb-4 text-center">Meus Agendamentos</h3>
            @if($agendamentos->isEmpty())
                <div class="alert alert-info">Você ainda não possui agendamentos.</div>
            @else
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Serviço</th>
                            <th>Profissional</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agendamentos as $agendamento)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($agendamento->data)->format('d/m/Y') }}</td>
                                <td>{{ substr($agendamento->horario,0,5) }}</td>
                                <td>
                                    {{ $agendamento->servicoModel->nome ?? $agendamento->servico }}
                                    @if($agendamento->servicoModel)
                                        <br><small class="text-muted">R$ {{ number_format($agendamento->servicoModel->valor, 2, ',', '.') }}</small>
                                    @endif
                                </td>
                                <td>{{ $agendamento->funcionario->nome ?? $agendamento->profissional ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            <a href="{{ route('agendamento.create') }}" class="btn btn-dark mt-3 w-100">Novo Agendamento</a>
            @can('access-owner-area')
                <a href="{{ route('owner.dashboard') }}" class="btn btn-outline-dark mt-2 w-100">Area da dona</a>
            @endcan
            <form method="POST" action="/logout" class="mt-2">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100">Sair</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>