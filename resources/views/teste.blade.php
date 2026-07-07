<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teste | Studio Franciele Cesario</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f6f1eb;
            color: #2b1f1a;
        }

        main {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .card {
            width: min(100%, 680px);
            background: #fffaf6;
            border: 1px solid #eadbcf;
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 20px 60px rgba(72, 45, 30, 0.08);
        }

        .eyebrow {
            font-size: 12px;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #a07147;
            margin-bottom: 12px;
        }

        h1 {
            margin: 0 0 12px;
            font-size: 32px;
        }

        p {
            margin: 0 0 16px;
            line-height: 1.6;
        }

        .status {
            display: inline-block;
            padding: 10px 14px;
            border-radius: 999px;
            background: #e8f6ec;
            color: #22633a;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <main>
        <section class="card">
            <div class="eyebrow">Rota de teste</div>
            <h1>Pagina de teste carregada</h1>
            <p>Se voce esta vendo esta tela, a rota <strong>/teste</strong> e a view <strong>teste.blade.php</strong> estao funcionando no projeto.</p>
            <div class="status">Conteudo de teste carregado com sucesso.</div>
        </section>
    </main>
</body>
</html>
