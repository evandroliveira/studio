<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento - Studio Franciele Cesario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html { height: 100%; margin: 0; padding: 0; }
        .video-bg { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; object-fit: cover; z-index: -1; }
        .overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.5); z-index: 0; }
        .agendamento-container { position: relative; z-index: 1; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { background: rgba(255,255,255,0.97); border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.2); }
        .step { display: flex; align-items: center; }
        .step-indicator { width: 32px; height: 32px; border-radius: 50%; background: #212529; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 10px; }
        .step.active .step-indicator { background: #ffc107; color: #212529; }
        .step:not(:last-child)::after { content: ''; display: block; width: 40px; height: 2px; background: #ccc; margin-left: 10px; }
        .step.active:not(:last-child)::after { background: #ffc107; }
    </style>
</head>
<body>
    <video class="video-bg" autoplay muted loop>
        <source src="/videos/studio2.mp4" type="video/mp4">
        Seu navegador não suporta vídeo em HTML5.
    </video>
    <div class="overlay"></div>
    <div class="agendamento-container">
        <div class="card p-4" style="min-width:340px;max-width:480px;width:100%;">
            <h3 class="mb-4 text-center">Agende seu horário</h3>
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
            <!-- Pipeline de etapas -->
            <div class="d-flex justify-content-center mb-4">
                <div class="step active"><div class="step-indicator">1</div>Data</div>
                <div class="step"><div class="step-indicator">2</div>Horário</div>
                <div class="step"><div class="step-indicator">3</div>Serviço</div>
            </div>
            <!-- Formulário interativo -->
            <form method="POST" action="/agendamento">
                @csrf
                <div class="mb-3">
                    <label for="data" class="form-label">Escolha a data</label>
                    <input type="date" class="form-control" id="data" name="data" required>
                </div>
                <div class="mb-3">
                    <label for="horario" class="form-label">Escolha o horário</label>
                    <input type="time" class="form-control" id="horario" name="horario" required>
                </div>
                <div class="mb-3">
                    <label for="servico" class="form-label">Serviço</label>
                    <select class="form-select" id="servico" name="servico" required>
                        <option value="">Selecione...</option>
                        <option value="Corte de cabelo">Corte de cabelo</option>
                        <option value="Coloração">Coloração</option>
                        <option value="Manicure">Manicure</option>
                        <option value="Pedicure">Pedicure</option>
                        <option value="Sobrancelha">Sobrancelha</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-dark w-100">Confirmar Agendamento</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>