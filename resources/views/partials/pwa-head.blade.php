<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
<link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
<meta name="theme-color" content="#1f1f1f">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="Studio Franciele">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
<link rel="apple-touch-icon-precomposed" href="{{ asset('apple-touch-icon.png') }}">
<link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">
<style>
    .pwa-install-banner {
        position: fixed;
        right: 16px;
        bottom: 16px;
        left: 16px;
        z-index: 9999;
        display: none;
        pointer-events: none;
    }

    .pwa-install-banner.is-visible {
        display: block;
    }

    .pwa-install-banner.is-modal {
        inset: 0;
        right: 0;
        bottom: 0;
        left: 0;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        padding: 18px;
        background: rgba(5, 5, 5, 0.48);
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
        width: 100%;
        margin-left: auto;
        pointer-events: auto;
    }

    .pwa-install-banner.is-modal .pwa-install-card {
        max-width: 430px;
        border-radius: 26px;
        padding: 18px;
        background:
            radial-gradient(circle at top right, rgba(198, 164, 111, 0.22), transparent 32%),
            linear-gradient(180deg, rgba(18, 18, 18, 0.98), rgba(28, 28, 28, 0.96));
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

    .pwa-install-guide {
        margin-bottom: 0.95rem;
        padding: 0.9rem;
        border-radius: 18px;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.07), rgba(255, 255, 255, 0.04));
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.06);
    }

    .pwa-install-guide[hidden] {
        display: none !important;
    }

    .pwa-install-preview {
        display: grid;
        gap: 0.85rem;
        margin-bottom: 0.9rem;
    }

    .pwa-install-app {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .pwa-install-app-icon {
        width: 58px;
        height: 58px;
        border-radius: 18px;
        flex: 0 0 58px;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.22);
        border: 1px solid rgba(255, 255, 255, 0.12);
    }

    .pwa-install-app-name {
        font-size: 1rem;
        font-weight: 800;
        color: #fff;
        margin-top: 0.45rem;
    }

    .pwa-install-app-caption {
        color: rgba(255, 255, 255, 0.72);
        font-size: 0.84rem;
        line-height: 1.35;
        margin-top: 0.2rem;
    }

    .pwa-install-browser-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.35rem 0.7rem;
        border-radius: 999px;
        background: rgba(198, 164, 111, 0.16);
        color: #f4d6a5;
        font-size: 0.76rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .pwa-install-flow {
        display: inline-flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.45rem;
    }

    .pwa-install-flow-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 2rem;
        padding: 0.35rem 0.75rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.8rem;
        font-weight: 700;
    }

    .pwa-install-flow-separator {
        color: rgba(255, 255, 255, 0.55);
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.08em;
    }

    .pwa-install-steps {
        display: grid;
        gap: 0.65rem;
    }

    .pwa-install-step {
        display: flex;
        align-items: flex-start;
        gap: 0.7rem;
    }

    .pwa-install-step-visual {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 14px;
        flex: 0 0 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(198, 164, 111, 0.16);
        color: #f4d6a5;
        border: 1px solid rgba(198, 164, 111, 0.24);
    }

    .pwa-install-step-visual svg {
        width: 1.2rem;
        height: 1.2rem;
        display: block;
        fill: none;
        stroke: currentColor;
        stroke-width: 1.8;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .pwa-install-step-body {
        display: grid;
        gap: 0.18rem;
    }

    .pwa-install-step-kicker {
        color: rgba(255, 255, 255, 0.54);
        font-size: 0.74rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .pwa-install-step-text {
        color: rgba(255, 255, 255, 0.88);
        font-size: 0.9rem;
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
        .pwa-install-banner:not(.is-modal) {
            left: auto;
            width: min(420px, calc(100vw - 32px));
        }

        .pwa-install-banner.is-modal {
            align-items: center;
            padding: 24px;
        }
    }

    @media (max-width: 575px) {
        .pwa-install-banner.is-modal {
            padding: 12px;
        }

        .pwa-install-banner.is-modal .pwa-install-card {
            border-radius: 24px 24px 18px 18px;
        }

        .pwa-install-flow {
            gap: 0.35rem;
        }
    }
</style>