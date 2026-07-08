# Salao da Fran

Aplicacao Laravel 12 para cadastro de clientes, agendamentos e Studio Franciele Cesario.

## Ambiente local

1. Instale as dependencias:

```bash
composer install
npm install
```

2. Crie o ambiente e gere a chave:

```bash
copy .env.example .env
php artisan key:generate
```

3. Rode as migrations e suba os assets:

```bash
php artisan migrate
npm run build
```

## Deploy na Hostinger

No Hostinger, os sintomas mais comuns de deploy incompleto neste projeto sao:

- tela inicial e login funcionando, mas cadastro/agendamento falhando
- erro 500 ao abrir ou confirmar agendamento
- erro 419 ou login inconsistente por cache/configuracao antiga

Use estas configuracoes no .env de producao:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seu-dominio.com
LOG_CHANNEL=errorlog

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=seu_banco
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
SESSION_SECURE_COOKIE=true

MAIL_MAILER=smtp
MAIL_SCHEME=tls
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=SMTP_USERNAME_AQUI
MAIL_PASSWORD=SMTP_PASSWORD_AQUI
MAIL_FROM_ADDRESS=no-reply@example.invalid
MAIL_FROM_NAME="Studio Franciele Cesario"
```

Depois do upload, execute no terminal SSH da hospedagem ou no terminal do painel:

```bash
php artisan optimize:clear
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Para validar o e-mail de confirmacao na Hostinger:

1. Crie a caixa postal real no hPanel antes de usar o endereco em MAIL_USERNAME.
2. Use smtp.hostinger.com com porta 587 e TLS.
3. Rode php artisan optimize:clear depois de salvar o .env.
4. Abra /diagnostico-hospedagem e confirme que mail.ready veio true.
5. Faça um agendamento de teste e confirme o horario pelo admin.

## Checklist de producao

1. Aponte o dominio para a pasta public do Laravel.
2. Confirme que vendor existe no servidor. Se nao existir, rode composer install --no-dev --optimize-autoloader.
3. Rode php artisan migrate --force sempre que publicar alteracoes de banco.
4. Se aparecer a mensagem de sistema de agendamento nao finalizado, faltou migration ou o cache de configuracao/rota esta antigo.
5. Se o navegador retornar 419, revise APP_URL, SESSION_SECURE_COOKIE e limpe cache com php artisan optimize:clear.
6. Se nao surgir storage/logs/laravel.log no servidor, use LOG_CHANNEL=errorlog, rode php artisan optimize:clear e abra /diagnostico-hospedagem para verificar banco, schema e permissao de escrita.

## Diagnostico rapido

Abra /diagnostico-hospedagem no navegador apos o deploy.

- Se retornar 404, o codigo novo nao foi publicado ou o route cache antigo ainda esta ativo.
- Se database.connected vier false, o banco da Hostinger nao esta acessivel com o .env atual.
- Se mail.ready vier false, o envio de e-mail ainda esta em modo log/local e a cliente nao recebera confirmacao real.
- Se missing_items vier preenchido, as migrations nao terminaram em producao.
- Se storage_logs_writable ou storage_sessions_writable vier false, falta permissao de escrita em storage.
