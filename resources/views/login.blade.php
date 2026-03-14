@extends('layouts.auth')

@section('title', 'Connexion — Lycée Ahmed Farah Ali')
@section('description', 'Espace de connexion réservé au personnel du Lycée Ahmed Farah Ali. Gestion des classes, des étudiants, des certificats scolaires et des rapports administratifs.')

@push('styles')
<style>
    /* ── Two-column wrapper ── */
    .login-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 5rem;
        align-items: center;
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
    }

    @media (max-width: 860px) {
        .login-grid {
            grid-template-columns: 1fr;
            gap: 3rem;
            max-width: 480px;
        }
        .login-left { display: none; }
    }

    /* ── LEFT COLUMN — Institutional identity ── */
    .login-left {
        color: #e8eaf0;
    }

    .school-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.4rem 0.9rem 0.4rem 0.4rem;
        border: 1px solid rgba(201,168,76,0.3);
        border-radius: 100px;
        background: rgba(201,168,76,0.06);
        margin-bottom: 2.5rem;
        backdrop-filter: blur(8px);
    }

    .badge-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: #c9a84c;
        box-shadow: 0 0 8px rgba(201,168,76,0.6);
        animation: pulse 2.5s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: 0.6; transform: scale(0.8); }
    }

    .badge-label {
        font-family: 'DM Sans', sans-serif;
        font-size: 0.72rem;
        font-weight: 500;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #c9a84c;
    }

    .login-left h1 {
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(2.4rem, 4vw, 3.4rem);
        font-weight: 600;
        line-height: 1.12;
        letter-spacing: -0.01em;
        color: #f9fafc;
        margin-bottom: 1.5rem;
    }

    .login-left h1 em {
        font-style: italic;
        color: #c9a84c;
    }

    .login-left p {
        font-size: 0.925rem;
        line-height: 1.8;
        color: #8b8fa8;
        max-width: 380px;
    }

    .divider {
        width: 48px;
        height: 2px;
        background: linear-gradient(90deg, #c9a84c, transparent);
        margin: 2rem 0;
        border-radius: 2px;
    }

    .feature-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-top: 2.5rem;
    }

    .feature-item {
        display: flex;
        align-items: flex-start;
        gap: 0.9rem;
    }

    .feature-icon {
        width: 32px; height: 32px;
        flex-shrink: 0;
        border-radius: 8px;
        border: 1px solid rgba(201,168,76,0.2);
        background: rgba(201,168,76,0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 1px;
    }

    .feature-icon svg {
        width: 14px; height: 14px;
        stroke: #c9a84c;
        fill: none;
        stroke-width: 1.8;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .feature-text strong {
        display: block;
        font-size: 0.85rem;
        font-weight: 500;
        color: #d1d5e0;
        margin-bottom: 0.15rem;
    }

    .feature-text span {
        font-size: 0.78rem;
        color: #6b7080;
        line-height: 1.5;
    }

    /* ── RIGHT COLUMN — Login card ── */
    .login-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        padding: 2.8rem 2.5rem;
        backdrop-filter: blur(24px);
        box-shadow:
            0 0 0 1px rgba(255,255,255,0.04) inset,
            0 32px 64px rgba(0, 0, 0, 0.4),
            0 0 80px rgba(201,168,76,0.04);
        position: relative;
        overflow: hidden;
    }

    /* Subtle top highlight */
    .login-card::before {
        content: '';
        position: absolute;
        top: 0; left: 10%; right: 10%;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(201,168,76,0.4), transparent);
    }

    /* ── Card Header ── */
    .card-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .logo-ring {
        width: 64px; height: 64px;
        border-radius: 50%;
        border: 1px solid rgba(201,168,76,0.3);
        background: linear-gradient(135deg, rgba(201,168,76,0.15) 0%, rgba(201,168,76,0.04) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        box-shadow: 0 0 0 6px rgba(201,168,76,0.05), 0 8px 24px rgba(0,0,0,0.3);
        position: relative;
    }

    .logo-ring::after {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 50%;
        border: 1px dashed rgba(201,168,76,0.15);
        animation: rotateSlow 20s linear infinite;
    }

    @keyframes rotateSlow {
        to { transform: rotate(360deg); }
    }

    .logo-ring svg {
        width: 28px; height: 28px;
        fill: #c9a84c;
    }

    .card-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.85rem;
        font-weight: 600;
        color: #f9fafc;
        letter-spacing: -0.01em;
        margin-bottom: 0.3rem;
    }

    .card-subtitle {
        font-size: 0.8rem;
        color: #6b7080;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        font-weight: 400;
    }

    /* ── Form Styles ── */
    .form-group {
        margin-bottom: 1.4rem;
    }

    .form-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 500;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #8b8fa8;
        margin-bottom: 0.55rem;
    }

    .input-wrap {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .input-icon svg {
        width: 16px; height: 16px;
        stroke: #4a5068;
        fill: none;
        stroke-width: 1.8;
        stroke-linecap: round;
        stroke-linejoin: round;
        transition: stroke 0.2s;
    }

    .form-input {
        width: 100%;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 10px;
        padding: 0.8rem 1rem 0.8rem 2.75rem;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.9rem;
        color: #e8eaf0;
        transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
        outline: none;
    }

    .form-input::placeholder {
        color: #3d4258;
    }

    .form-input:hover {
        border-color: rgba(255,255,255,0.14);
        background: rgba(255,255,255,0.06);
    }

    .form-input:focus {
        border-color: rgba(201,168,76,0.5);
        background: rgba(201,168,76,0.04);
        box-shadow: 0 0 0 3px rgba(201,168,76,0.08);
    }

    .form-input:focus ~ .input-icon svg,
    .input-wrap:focus-within .input-icon svg {
        stroke: rgba(201,168,76,0.7);
    }

    /* Password toggle */
    .toggle-btn {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4a5068;
        transition: color 0.2s;
        border-radius: 4px;
    }

    .toggle-btn:hover {
        color: #8b8fa8;
    }

    .toggle-btn svg {
        width: 16px; height: 16px;
        stroke: currentColor;
        fill: none;
        stroke-width: 1.8;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    /* Error messages */
    .field-error {
        margin-top: 0.45rem;
        font-size: 0.78rem;
        color: #f87171;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    /* Forgot password */
    .form-footer {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 1.8rem;
        margin-top: -0.6rem;
    }

    .forgot-link {
        font-size: 0.78rem;
        color: #6b7080;
        text-decoration: none;
        transition: color 0.2s;
    }

    .forgot-link:hover {
        color: #c9a84c;
    }

    /* Submit button */
    .btn-submit {
        width: 100%;
        padding: 0.85rem 1rem;
        border-radius: 10px;
        border: none;
        background: linear-gradient(135deg, #c9a84c 0%, #a8852e 100%);
        color: #0d1117;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.875rem;
        font-weight: 600;
        letter-spacing: 0.04em;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
        box-shadow: 0 4px 20px rgba(201,168,76,0.25);
        position: relative;
        overflow: hidden;
    }

    .btn-submit::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 60%);
        opacity: 0;
        transition: opacity 0.2s;
    }

    .btn-submit:hover {
        opacity: 0.92;
        transform: translateY(-1px);
        box-shadow: 0 8px 28px rgba(201,168,76,0.35);
    }

    .btn-submit:hover::before {
        opacity: 1;
    }

    .btn-submit:active {
        transform: translateY(0);
        opacity: 1;
    }

    /* ── Card Footer ── */
    .card-footer {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(255,255,255,0.05);
        text-align: center;
    }

    .card-footer p {
        font-size: 0.72rem;
        color: #3d4258;
        letter-spacing: 0.04em;
    }

    .card-footer strong {
        color: #4a5068;
        font-weight: 500;
    }
</style>
@endpush

@section('content')

<div class="login-grid stagger">

    {{-- ── Left Column: Identity ── --}}
    <div class="login-left">
        <div class="school-badge">
            <div class="badge-dot"></div>
            <span class="badge-label">Lycée Ahmed Farah Ali</span>
        </div>
        <h1>
            Espace<br>
            <em>Administratif</em>
        </h1>

        <div class="divider"></div>

        <p>
            Plateforme officielle de gestion du
            <strong style="color: #d1d5e0;">Lycée Ahmed Farah Ali</strong>.
            Accédez aux outils de suivi des dossiers, de gestion des
            certificats scolaires et d'administration numérique de l'établissement.
        </p>

        <div class="feature-list">
            <div class="feature-item">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </div>
                <div class="feature-text">
                    <strong>Certificats scolaires</strong>
                    <span>Génération et suivi des documents administratifs</span>
                </div>
            </div>

            <div class="feature-item">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div class="feature-text">
                    <strong>Dossiers élèves</strong>
                    <span>Gestion centralisée des informations scolaires</span>
                </div>
            </div>

            <div class="feature-item">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                </div>
                <div class="feature-text">
                    <strong>Tableau de bord</strong>
                    <span>Vue d'ensemble des demandes administratives</span>
                </div>
            </div>
        </div>

    </div>


    {{-- ── Right Column: Login Card ── --}}
    <div class="login-card">

        <div class="card-header">
            <div class="logo-ring">
                <svg viewBox="0 0 24 24">
                    <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zm0 2.18L18.09 9L12 12.73L5.91 9L12 5.18z"/>
                    <path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/>
                </svg>
            </div>
            <h2 class="card-title">Connexion</h2>
            <p class="card-subtitle">Lycée Ahmed Farah Ali</p>
        </div>

        <form method="POST" action="{{ route('authenticate') }}">
            @csrf

            {{-- Email --}}
            <div class="form-group">
                <label for="email" class="form-label">Adresse Email</label>
                <div class="input-wrap">
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="proviseur@lycee-ahmed-farah.dj"
                        class="form-input">
                    <span class="input-icon">
                        <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </span>
                </div>
                @error('email')
                    <p class="field-error">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label for="password" class="form-label">Mot de Passe</label>
                <div class="input-wrap">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••••••"
                        class="form-input"
                        style="padding-right: 2.75rem;">
                    <span class="input-icon">
                        <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </span>
                    <button type="button" id="togglePassword" class="toggle-btn" aria-label="Afficher le mot de passe">
                        <svg id="eyeIcon" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                @error('password')
                    <p class="field-error">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Forgot --}}
            <div class="form-footer">
                <a href="{{ route('password.request') }}" class="forgot-link">
                    Mot de passe oublié ?
                </a>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-submit">
                Accéder à l'espace administratif
            </button>

        </form>

        <div class="card-footer">
            <p>Accès réservé au <strong>personnel autorisé</strong> de l'établissement</p>
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
    const toggleBtn  = document.getElementById('togglePassword');
    const passInput  = document.getElementById('password');
    const eyeIcon    = document.getElementById('eyeIcon');

    const eyeOpen   = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
    const eyeClosed = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>`;

    toggleBtn.addEventListener('click', () => {
        const isHidden = passInput.type === 'password';
        passInput.type = isHidden ? 'text' : 'password';
        eyeIcon.innerHTML = isHidden ? eyeClosed : eyeOpen;
        toggleBtn.setAttribute('aria-label', isHidden ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
    });
</script>
@endpush