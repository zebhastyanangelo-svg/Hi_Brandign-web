@extends('layouts.admin')

@section('title', 'Configuración')

@section('content')
<div class="admin-dashboard animate__animated animate__fadeInUp animate__faster">
    <header class="admin-dashboard-header">
        <div>
            <p class="eyebrow">Administración</p>
            <h1>Configuración del <em>Panel</em></h1>
        </div>
    </header>

    <div class="settings-grid">
        <section class="directivo-module" aria-labelledby="settings-account-title">
            <header class="directivo-module-head">
                <div>
                    <p class="module-index">Cuenta directiva</p>
                    <h2 id="settings-account-title">Perfil de acceso</h2>
                </div>
            </header>
            <div class="guide-body">
                <dl class="settings-list">
                    <div class="summary-price-row">
                        <dt>Nombre</dt>
                        <dd>{{ $adminUser->name }}</dd>
                    </div>
                    <div class="summary-price-row">
                        <dt>Correo corporativo</dt>
                        <dd>{{ $adminUser->email }}</dd>
                    </div>
                    <div class="summary-price-row">
                        <dt>Guard de autenticación</dt>
                        <dd>admin (sesión cifrada)</dd>
                    </div>
                    <div class="summary-price-row">
                        <dt>Protocolo</dt>
                        <dd>TLS 1.3 · 2FA recomendado</dd>
                    </div>
                </dl>
            </div>
        </section>

        <section class="directivo-module" aria-labelledby="settings-password-title">
            <header class="directivo-module-head">
                <div>
                    <p class="module-index">Seguridad</p>
                    <h2 id="settings-password-title">Actualizar contraseña</h2>
                </div>
            </header>

            <form method="POST" action="{{ route('admin.settings.password') }}" class="settings-form">
                @csrf

                @if ($errors->any())
                    <div class="form-errors animate__animated animate__shakeX" role="alert">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="icon-field">
                    <label for="current_password">Contraseña actual</label>
                    <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    <input type="password" id="current_password" name="current_password" required autocomplete="current-password">
                    @error('current_password')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="icon-field">
                    <label for="password">Nueva contraseña</label>
                    <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    <input type="password" id="password" name="password" required minlength="8" autocomplete="new-password">
                    @error('password')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="icon-field">
                    <label for="password_confirmation">Confirmar nueva contraseña</label>
                    <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" autocomplete="new-password">
                    @error('password_confirmation')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <button type="submit" class="button button--rust">
                    <span aria-hidden="true">✓</span> Guardar contraseña
                </button>
            </form>
        </section>
    </div>
</div>
@endsection
