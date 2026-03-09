<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Connexion - Lycée Ahmed Farah Ali')</title>
    <meta name="description" content="@yield('description', 'Système de gestion scolaire du Lycée Ahmed Farah Ali')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <style>
        :root {
            --ink:        #0d1117;
            --ink-light:  #1c2333;
            --gold:       #c9a84c;
            --gold-light: #e2c47a;
            --stone:      #8b8fa8;
            --mist:       #e8eaf0;
            --white:      #f9fafc;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { font-size: 16px; }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--ink);
            min-height: 100vh;
            overflow-x: hidden;
            color: var(--white);
        }

        /* ── Background Architecture ── */
        .auth-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }

        /* Deep navy base */
        .auth-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 50%, #0f1d3a 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 80% 20%, #0a1628 0%, transparent 55%),
                linear-gradient(160deg, #080e1a 0%, #0d1117 40%, #111827 100%);
        }

        /* Subtle geometric grid */
        .auth-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(201,168,76,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(201,168,76,0.04) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(ellipse 70% 70% at 50% 50%, black 30%, transparent 80%);
        }

        /* Gold accent orb */
        .auth-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            pointer-events: none;
        }
        .auth-orb-1 {
            width: 600px; height: 600px;
            top: -200px; right: -100px;
            background: radial-gradient(circle, rgba(201,168,76,0.12) 0%, transparent 70%);
            animation: orbDrift 18s ease-in-out infinite alternate;
        }
        .auth-orb-2 {
            width: 400px; height: 400px;
            bottom: -100px; left: 10%;
            background: radial-gradient(circle, rgba(59,130,246,0.07) 0%, transparent 70%);
            animation: orbDrift 24s ease-in-out infinite alternate-reverse;
        }

        @keyframes orbDrift {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(30px, -20px) scale(1.08); }
        }

        /* Decorative diagonal stripe */
        .auth-stripe {
            position: absolute;
            top: 0; left: 55%;
            width: 1px;
            height: 100%;
            background: linear-gradient(
                to bottom,
                transparent 0%,
                rgba(201,168,76,0.15) 20%,
                rgba(201,168,76,0.3) 50%,
                rgba(201,168,76,0.15) 80%,
                transparent 100%
            );
            transform: rotate(8deg) translateX(-50%);
            transform-origin: top center;
        }

        /* ── Flash Messages ── */
        .flash-container {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 100;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            max-width: 380px;
        }

        /* ── Main Layout ── */
        main {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
        }

        /* ── Page Enter Animation ── */
        .page-enter {
            animation: pageEnter 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes pageEnter {
            from {
                opacity: 0;
                transform: translateY(24px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Staggered children ── */
        .stagger > * {
            opacity: 0;
            animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .stagger > *:nth-child(1) { animation-delay: 0.1s; }
        .stagger > *:nth-child(2) { animation-delay: 0.2s; }
        .stagger > *:nth-child(3) { animation-delay: 0.3s; }
        .stagger > *:nth-child(4) { animation-delay: 0.4s; }
        .stagger > *:nth-child(5) { animation-delay: 0.5s; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>

    {{-- ── Layered Background ── --}}
    <div class="auth-bg" aria-hidden="true">
        <div class="auth-orb auth-orb-1"></div>
        <div class="auth-orb auth-orb-2"></div>
        <div class="auth-stripe"></div>
    </div>

    {{-- ── Flash Messages ── --}}
    <div class="flash-container" role="alert" aria-live="polite">
        @if (session('success'))
            <x-flash-message type="success" :message="session('success')" />
        @endif
        @if (session('error'))
            <x-flash-message type="error" :message="session('error')" />
        @endif
        @if (session('warning'))
            <x-flash-message type="warning" :message="session('warning')" />
        @endif
        @if (session('info'))
            <x-flash-message type="info" :message="session('info')" />
        @endif
    </div>

    {{-- ── Page Content ── --}}
    <main>
        <div class="page-enter w-full">
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>

</html>