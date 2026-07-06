<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Agendamento confirmado</title>
</head>
<body style="margin:0;padding:24px;background:#f6efe9;font-family:Arial,Helvetica,sans-serif;color:#2c1d18;">
    <div style="max-width:640px;margin:0 auto;background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #ead8cd;box-shadow:0 10px 28px rgba(44,29,24,0.08);">
        <div style="padding:24px 24px 18px;background:linear-gradient(135deg,#2b1e1a,#8f5a4a);color:#fff7f3;">
            <div style="font-size:12px;letter-spacing:0.14em;text-transform:uppercase;font-weight:700;opacity:0.78;">Studio Franciele Cesario</div>
            <h1 style="margin:10px 0 0;font-size:28px;line-height:1.1;">Seu horario foi confirmado</h1>
        </div>

        <div style="padding:24px;">
            <p style="margin-top:0;font-size:16px;line-height:1.6;">Ola, {{ $agendamento->user->name ?? 'cliente' }}.</p>
            <p style="font-size:16px;line-height:1.6;">Seu agendamento foi confirmado pela administracao. Veja os detalhes:</p>

            <div style="background:#faf4ef;border:1px solid #efdfd5;border-radius:14px;padding:18px;line-height:1.8;">
                <div><strong>Data:</strong> {{ \Carbon\Carbon::parse($agendamento->data)->format('d/m/Y') }}</div>
                <div><strong>Horario:</strong> {{ substr($agendamento->horario, 0, 5) }}</div>
                <div><strong>Servico:</strong> {{ $agendamento->servicoModel->nome ?? $agendamento->servico }}</div>
                <div><strong>Profissional:</strong> {{ $agendamento->funcionario->nome ?? $agendamento->profissional ?? 'Nao informada' }}</div>
                <div><strong>Especialidade:</strong> {{ $agendamento->funcionario->especialidade ?? 'Nao informada' }}</div>
            </div>

            <p style="margin:18px 0 0;font-size:15px;line-height:1.6;">Se precisar remarcar, entre em contato com o salao com antecedencia.</p>
        </div>
    </div>
</body>
</html>