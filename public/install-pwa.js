(() => {
    const storageKey = 'studio-pwa-install-state-v1';
    const banner = document.getElementById('pwa-install-banner');
    const title = document.getElementById('pwa-install-title');
    const copy = document.getElementById('pwa-install-copy');
    const actionButton = document.getElementById('pwa-install-action');
    const closeButton = document.getElementById('pwa-install-close');

    if (!banner || !title || !copy || !actionButton || !closeButton) {
        return;
    }

    const userAgent = window.navigator.userAgent.toLowerCase();
    const isIos = /iphone|ipad|ipod/.test(userAgent);
    const isMobile = /android|iphone|ipad|ipod/.test(userAgent);
    const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;

    if (!isMobile || isStandalone) {
        return;
    }

    const getState = () => {
        try {
            return window.localStorage.getItem(storageKey);
        } catch (error) {
            return null;
        }
    };

    const setState = (value) => {
        try {
            window.localStorage.setItem(storageKey, value);
        } catch (error) {
            // Ignora bloqueios de storage do navegador.
        }
    };

    const currentState = getState();

    if (currentState === 'dismissed' || currentState === 'installed') {
        return;
    }

    let deferredPrompt = null;
    let currentMode = 'manual';
    let hasShownBanner = false;
    let fallbackTimer = null;

    const hideBanner = () => {
        banner.hidden = true;
        banner.classList.remove('is-visible');
    };

    const showBanner = (mode) => {
        currentMode = mode;
        hasShownBanner = true;

        if (mode === 'native') {
            title.textContent = 'Instale o app no celular';
            copy.textContent = 'Adicione o Studio Franciele Cesario na tela inicial para abrir mais rapido como aplicativo.';
            actionButton.textContent = 'Instalar';
            closeButton.hidden = false;
        } else if (mode === 'ios') {
            title.textContent = 'Adicione na tela inicial';
            copy.textContent = 'No iPhone ou iPad, toque em Compartilhar e depois em Adicionar a Tela de Inicio.';
            actionButton.textContent = 'Entendi';
            closeButton.hidden = true;
        } else {
            title.textContent = 'Instale pelo menu do navegador';
            copy.textContent = 'Se o navegador nao abrir o instalador automaticamente, use o menu do navegador e escolha Instalar app ou Adicionar a tela inicial.';
            actionButton.textContent = 'Entendi';
            closeButton.hidden = true;
        }

        banner.hidden = false;
        banner.classList.add('is-visible');
    };

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            const serviceWorkerUrl = window.pwaInstallConfig && window.pwaInstallConfig.serviceWorkerUrl
                ? window.pwaInstallConfig.serviceWorkerUrl
                : '/sw.js';

            navigator.serviceWorker.register(serviceWorkerUrl).catch(() => {
                // Mantem a pagina funcional mesmo se o service worker falhar.
            });
        });
    }

    window.addEventListener('beforeinstallprompt', (event) => {
        event.preventDefault();
        deferredPrompt = event;
        if (fallbackTimer) {
            window.clearTimeout(fallbackTimer);
        }

        if (getState() !== 'dismissed' && getState() !== 'installed') {
            showBanner('native');
        }
    });

    window.addEventListener('appinstalled', () => {
        setState('installed');
        hideBanner();
        deferredPrompt = null;
    });

    window.addEventListener('load', () => {
        fallbackTimer = window.setTimeout(() => {
            if (getState() === 'dismissed' || getState() === 'installed' || deferredPrompt || hasShownBanner) {
                return;
            }

            if (isIos) {
                showBanner('ios');
                return;
            }

            showBanner('manual');
        }, 1200);
    });

    actionButton.addEventListener('click', async () => {
        if (currentMode === 'native' && deferredPrompt) {
            deferredPrompt.prompt();

            const result = await deferredPrompt.userChoice.catch(() => null);

            setState(result?.outcome === 'accepted' ? 'installed' : 'dismissed');
            hideBanner();
            deferredPrompt = null;
            return;
        }

        setState('dismissed');
        hideBanner();
    });

    closeButton.addEventListener('click', () => {
        setState('dismissed');
        hideBanner();
    });
})();