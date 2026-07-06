<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
<meta name="theme-color" content="#1f1f1f">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="Studio Franciele">
<link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">
<style>
    .pwa-install-banner {
        position: fixed;
        right: 16px;
        bottom: 16px;
        left: 16px;
        z-index: 9999;
        display: none;
    }

    .pwa-install-banner.is-visible {
        display: block;
    }

    .pwa-install-card {
        background: rgba(17, 17, 17, 0.94);
        color: #fff;
        border-radius: 18px;
        padding: 16px;
        box-shadow: 0 18px 45px rgba(0, 0, 0, 0.32);
        border: 1px solid rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
    }

    .pwa-install-eyebrow {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        color: rgba(255, 255, 255, 0.64);
        margin-bottom: 0.35rem;
        font-weight: 700;
    }

    .pwa-install-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 0.35rem;
    }

    .pwa-install-copy {
        margin-bottom: 0.85rem;
        color: rgba(255, 255, 255, 0.82);
        font-size: 0.92rem;
        line-height: 1.4;
    }

    .pwa-install-actions {
        display: flex;
        gap: 0.6rem;
        flex-wrap: wrap;
    }

    .pwa-install-primary,
    .pwa-install-secondary {
        border: 0;
        border-radius: 999px;
        padding: 0.65rem 1rem;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .pwa-install-primary {
        background: linear-gradient(135deg, #c6a46f, #a77b39);
        color: #111;
        flex: 1 1 160px;
    }

    .pwa-install-secondary {
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        flex: 1 1 120px;
    }

    @media (min-width: 768px) {
        .pwa-install-banner {
            left: auto;
            width: min(420px, calc(100vw - 32px));
        }
    }
</style>