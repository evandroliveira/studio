<div class="pwa-install-banner" id="pwa-install-banner" hidden>
    <div class="pwa-install-card">
        <div class="pwa-install-eyebrow">Tela inicial</div>
        <div class="pwa-install-title" id="pwa-install-title">Instale o app no celular</div>
        <div class="pwa-install-copy" id="pwa-install-copy">Adicione o Studio Franciele Cesario na tela inicial para abrir mais rapido como aplicativo.</div>
        <div class="pwa-install-guide" id="pwa-install-guide" hidden>
            <div class="pwa-install-preview">
                <div class="pwa-install-app">
                    <img src="{{ asset('apple-touch-icon.png') }}" alt="" class="pwa-install-app-icon">
                    <div>
                        <div class="pwa-install-browser-badge" id="pwa-install-browser-badge">Safari</div>
                        <div class="pwa-install-app-name">Studio Franciele</div>
                        <div class="pwa-install-app-caption" id="pwa-install-app-caption">Fluxo rapido para instalar no iPhone.</div>
                    </div>
                </div>
                <div class="pwa-install-flow" aria-hidden="true">
                    <span class="pwa-install-flow-chip">Safari</span>
                    <span class="pwa-install-flow-separator">-></span>
                    <span class="pwa-install-flow-chip">Tela inicial</span>
                </div>
            </div>
            <div class="pwa-install-steps">
                <div class="pwa-install-step">
                    <span class="pwa-install-step-visual" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="8"></circle>
                            <path d="M12 12l4-4"></path>
                            <path d="M10.5 9.5l5.5-1.5-1.5 5.5"></path>
                        </svg>
                    </span>
                    <div class="pwa-install-step-body">
                        <span class="pwa-install-step-kicker">Passo 1</span>
                        <span class="pwa-install-step-text" id="pwa-install-step-1">Abra esta pagina no Safari.</span>
                    </div>
                </div>
                <div class="pwa-install-step">
                    <span class="pwa-install-step-visual" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 16V5"></path>
                            <path d="M8.5 8.5 12 5l3.5 3.5"></path>
                            <path d="M6 13v4a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1v-4"></path>
                        </svg>
                    </span>
                    <div class="pwa-install-step-body">
                        <span class="pwa-install-step-kicker">Passo 2</span>
                        <span class="pwa-install-step-text" id="pwa-install-step-2">Toque em Compartilhar.</span>
                    </div>
                </div>
                <div class="pwa-install-step">
                    <span class="pwa-install-step-visual" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 7v10"></path>
                            <path d="M7 12h10"></path>
                            <path d="M5 6.5A1.5 1.5 0 0 1 6.5 5h11A1.5 1.5 0 0 1 19 6.5v11a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 5 17.5z"></path>
                        </svg>
                    </span>
                    <div class="pwa-install-step-body">
                        <span class="pwa-install-step-kicker">Passo 3</span>
                        <span class="pwa-install-step-text" id="pwa-install-step-3">Escolha Adicionar a Tela de Inicio.</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="pwa-install-actions">
            <button type="button" class="pwa-install-primary" id="pwa-install-action">Instalar</button>
            <button type="button" class="pwa-install-secondary" id="pwa-install-close">Agora nao</button>
        </div>
    </div>
</div>
<div>
</div>
<script>
    window.pwaInstallConfig = {
        serviceWorkerUrl: "{{ asset('sw.js') }}",
    };
</script>
<script src="{{ asset('install-pwa.js') }}"></script>