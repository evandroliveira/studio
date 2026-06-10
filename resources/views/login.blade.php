<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Studio Franciele Cesario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .card .btn-dark {
            background-color: rgba(31, 31, 31, 0.92);
            border-color: rgba(31, 31, 31, 0.92);
        }

        .card .btn-dark:hover {
            background-color: rgba(31, 31, 31, 1);
            border-color: rgba(31, 31, 31, 1);
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
            <h3 class="mb-3 text-center">Entrar no Studio Franciele Cesario</h3>
            <form method="POST" action="/login">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-dark w-100">Entrar</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>