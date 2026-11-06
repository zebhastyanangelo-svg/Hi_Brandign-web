@php $loginContext = $loginContext ?? 'page'; @endphp
<div class="admin-login-card admin-login-card--{{ $loginContext }} animate__animated animate__fadeInUp animate__faster">
    <div class="admin-login-head">
        <a class="brand" href="{{ route('home') }}" aria-label="Hi Branding, inicio">
            <span class="brand-monogram">hb</span>
            <span class="brand-name">Hi Branding</span>
        </a>
        <span class="restricted-badge">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            Área Restringida
        </span>
    </div>

    <div class="admin-login-copy">
        <h1>Acceso Privado <em>Dirección</em></h1>
        <p>Portal de gestión administrativa del club.</p>
    </div>

    <form method="POST" action="{{ route('admin.login.submit') }}" class="admin-login-form">
        @csrf

        @if ($errors->any())
            <div class="form-errors animate__animated animate__shakeX" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="icon-field">
            <label for="login_email">Correo Electrónico Corporativo</label>
            <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            <input type="email" id="login_email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="nombre@hibranding.com">
            @error('email')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        <div class="icon-field">
            <label for="login_password">Contraseña</label>
            <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            <input type="password" id="login_password" name="password" required autocomplete="current-password" placeholder="••••••••••">
            @error('password')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        <label class="checkbox-label">
            <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
            <span>Mantener sesión segura en este dispositivo</span>
        </label>

        <button type="submit" class="button button--rust button--full admin-login-submit">
            <span>Ingresar al Panel de Control</span>
            <span aria-hidden="true">→</span>
        </button>

        <p class="admin-login-security">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
            Autenticación protegida con cifrado TLS 1.3 y verificación en dos pasos (2FA) para cuentas directivas.
        </p>
    </form>

    <p class="admin-login-footer">
        <span>Protocolo Cifrado TLS 1.3</span>
        <span aria-hidden="true">·</span>
        <span>Soporte Técnico Interno</span>
    </p>
</div>
