<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Studio Franciele Cesario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('partials.pwa-head')
    <style>
        body, html { height: 100%; margin: 0; padding: 0; }
        .video-bg { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; object-fit: cover; z-index: -1; }
        .overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.5); z-index: 0; }
        .login-container { position: relative; z-index: 1; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card {
            background: rgba(255,255,255,0.48);
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.2);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.14);
            color: #1f1f1f;
        }

        .card .form-label,
        .card h3 {
            color: #1f1f1f;
        }

        .card .form-control {
            background: rgba(255, 255, 255, 0.72);
            border-color: rgba(0, 0, 0, 0.12);
            color: #1f1f1f;
        }

        .card .form-control::placeholder {
            color: rgba(31, 31, 31, 0.6);
        }

        .card .form-control:focus {
            background: rgba(255, 255, 255, 0.86);
            border-color: rgba(31, 31, 31, 0.3);
            box-shadow: 0 0 0 0.2rem rgba(31, 31, 31, 0.12);
        }

        .password-toggle-group .form-control {
            border-right: 0;
        }

        .password-toggle-group .btn {
            background: rgba(255, 255, 255, 0.72);
            border-color: rgba(0, 0, 0, 0.12);
            color: #1f1f1f;
            font-weight: 600;
        }

        .password-toggle-group .btn:hover,
        .password-toggle-group .btn:focus {
            background: rgba(255, 255, 255, 0.86);
            color: #1f1f1f;
            box-shadow: none;
        }

        .card .btn-dark {
            background-color: rgba(31, 31, 31, 0.92);
            border-color: rgba(31, 31, 31, 0.92);
        }

        .card .btn-dark:hover {
            background-color: rgba(31, 31, 31, 1);
            border-color: rgba(31, 31, 31, 1);
        }

        .auth-links {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            font-size: 0.92rem;
            flex-wrap: wrap;
        }

        .auth-links a,
        .form-check-label,
        .form-check-input {
            color: #1f1f1f;
        }

        .auth-links a {
            text-decoration: none;
        }

        .auth-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <video class="video-bg" autoplay muted loop>
        <source src="/videos/studio2.mp4" type="video/mp4">
        Seu navegador não suporta vídeo em HTML5.
    </video>
    <div class="overlay"></div>
    <div class="login-container">
        <div class="card p-4" style="min-width:320px;max-width:400px;width:100%;">
            <h3 class="mb-3 text-center">Studio Franciele Cesario</h3>

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

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <div class="input-group password-toggle-group">
                        <input type="password" class="form-control" id="password" name="password" required>
                        <button type="button" class="btn btn-outline-secondary" data-password-toggle data-target="password">Mostrar</button>
                    </div>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember" @checked(old('remember'))>
                    <label class="form-check-label" for="remember">Manter conectado</label>
                </div>
                <button type="submit" class="btn btn-dark w-100">Entrar</button>
            </form>

            <div class="auth-links mt-3">
                <a href="{{ route('password.request') }}">Esqueci a senha</a>
                <a href="{{ route('register') }}">Novo cliente</a>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.target);

                if (!input) {
                    return;
                }

                const showingPassword = input.type === 'text';
                input.type = showingPassword ? 'password' : 'text';
                button.textContent = showingPassword ? 'Mostrar' : 'Ocultar';
            });
        });
    </script>
    @include('partials.pwa-install')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>