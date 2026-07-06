<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar senha - Studio Franciele Cesario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('partials.pwa-head')
    <style>
        body, html { height: 100%; margin: 0; padding: 0; }
        .video-bg { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; object-fit: cover; z-index: -1; }
        .overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.5); z-index: 0; }
        .auth-container { position: relative; z-index: 1; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 16px; }
        .card { background: rgba(255,255,255,0.48); border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.2); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.14); color: #1f1f1f; }
        .card .form-label, .card h3, .card p, .card a { color: #1f1f1f; }
        .card .form-control { background: rgba(255, 255, 255, 0.72); border-color: rgba(0, 0, 0, 0.12); color: #1f1f1f; }
        .card .form-control:focus { background: rgba(255, 255, 255, 0.86); border-color: rgba(31, 31, 31, 0.3); box-shadow: 0 0 0 0.2rem rgba(31, 31, 31, 0.12); }
        .card .btn-dark { background-color: rgba(31, 31, 31, 0.92); border-color: rgba(31, 31, 31, 0.92); }
        .helper-box {
            background: rgba(255, 255, 255, 0.54);
            border: 1px solid rgba(31, 31, 31, 0.08);
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 0.92rem;
        }
    </style>
</head>
<body>
    <video class="video-bg" autoplay muted loop>
        <source src="/videos/studio2.mp4" type="video/mp4">
        Seu navegador não suporta vídeo em HTML5.
    </video>
    <div class="overlay"></div>
    <div class="auth-container">
        <div class="card p-4" style="min-width:320px;max-width:420px;width:100%;">
            <h3 class="mb-3 text-center">Recuperar senha</h3>
            <p class="mb-3 text-center">Informe seu e-mail para receber o link de redefinição.</p>

            @if(config('mail.default') === 'log')
                <div class="helper-box mb-3">
                    No ambiente local, o link de redefinição será gravado em storage/logs/laravel.log.
                </div>
            @endif

            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>
                <button type="submit" class="btn btn-dark w-100">Enviar link</button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}">Voltar ao login</a>
            </div>

            <div class="text-center mt-2">
                <a href="{{ route('register') }}">Criar nova conta</a>
            </div>
        </div>
    </div>
    @include('partials.pwa-install')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>