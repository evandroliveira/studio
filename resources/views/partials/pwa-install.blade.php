<div class="pwa-install-banner" id="pwa-install-banner" hidden>
    <div class="pwa-install-card">
        <div class="pwa-install-eyebrow">Tela inicial</div>
        <div class="pwa-install-title" id="pwa-install-title">Instale o app no celular</div>
        <div class="pwa-install-copy" id="pwa-install-copy">Adicione o Studio Franciele Cesario na tela inicial para abrir mais rapido como aplicativo.</div>
        <div class="pwa-install-actions">
            <button type="button" class="pwa-install-primary" id="pwa-install-action">Instalar</button>
            <button type="button" class="pwa-install-secondary" id="pwa-install-close">Agora nao</button>
        </div>
    </div>
</div>
<div>
    <h1>Teste</h1>
</div>
<script>
    window.pwaInstallConfig = {
        serviceWorkerUrl: "{{ asset('sw.js') }}",
    };
</script>
<script src="{{ asset('install-pwa.js') }}"></script>