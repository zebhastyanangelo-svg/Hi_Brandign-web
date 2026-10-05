@extends('layouts.app')

@section('title', 'Acceso Administrativo')

@section('content')
<main class="admin-login-page">
    <div class="admin-login-card animate__animated animate__fadeInUp">
        <div class="admin-login-header">
            <span class="brand-monogram admin-brand">hb</span>
            <h1>Panel de Administración</h1>
            <p class="eyebrow">Gestión de eventos y validación de pagos</p>
        </div>

        <form method="POST" action="{{ route('admin.login.submit') }}" class="admin-login-form">
            @csrf

            @if ($errors->any())
                <div class="form-errors animate__animated animate__shakeX">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>

            <div class="form-group form-row">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                    <span>Recordarme</span>
                </label>
            </div>

            <button type="submit" class="button button--rust button--full animate__animated animate__fadeInUp animate__delay-1s">
                Iniciar sesión <span aria-hidden="true">→</span>
            </button>
        </form>

        <p class="admin-login-footer">
            <a href="{{ route('events.index') }}" class="text-link">
                Volver al sitio <span aria-hidden="true">←</span>
            </a>
        </p>
    </div>
</main>
@endsection

@push('scripts')
<style>
.admin-login-page {
    min-height: calc(100vh - 82px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 5vw;
}

.admin-login-card {
    width: 100%;
    max-width: 420px;
    padding: 48px 40px;
    background: var(--paper-soft);
    border: 1px solid var(--line);
    box-shadow: 0 20px 60px rgb(33 30 29 / 15%);
}

.admin-login-header {
    text-align: center;
    margin-bottom: 36px;
}

.admin-brand {
    width: 56px;
    height: 56px;
    font-size: 20px;
    margin: 0 auto 20px;
    background: var(--rust);
}

.admin-login-header h1 {
    margin: 0 0 8px;
    font-size: 32px;
}

.admin-login-header .eyebrow {
    margin-bottom: 0;
}

.admin-login-form {
    display: grid;
    gap: 20px;
}

.form-group {
    display: grid;
    gap: 8px;
}

.form-group label {
    font-size: 11px;
    font-weight: 600;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: .5px;
}

.form-group input[type="email"],
.form-group input[type="password"] {
    width: 100%;
    min-height: 48px;
    padding: 12px 14px;
    color: var(--ink);
    background: white;
    border: 1px solid var(--line);
    border-radius: 0;
    font-size: 14px;
    transition: border-color .2s ease, outline .2s ease;
}

.form-group input[type="email"]:focus,
.form-group input[type="password"]:focus {
    outline: 2px solid #c48869;
    outline-offset: 1px;
    border-color: transparent;
}

.form-row {
    display: flex;
    align-items: center;
}

.checkbox-label {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-size: 12px;
    color: var(--ink);
    cursor: pointer;
}

.checkbox-label input[type="checkbox"] {
    width: 16px;
    height: 16px;
    accent-color: var(--rust);
}

.button--full {
    width: 100%;
}

.form-errors {
    padding: 14px 16px;
    color: #a43b2b;
    background: #fdf0ed;
    border: 1px solid #e8c8c0;
    font-size: 12px;
}

.form-errors p {
    margin: 0;
}

.admin-login-footer {
    margin-top: 28px;
    text-align: center;
    font-size: 12px;
}

@media (max-width: 480px) {
    .admin-login-card {
        padding: 36px 24px;
    }
}
</style>
@endpush