<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f8f4ee">
    <meta name="description" content="Hi Branding: espacios, comunidad y acompañamiento para quienes están construyendo lo que sigue.">
    <title>@yield('title', 'Un lugar para lo que sigue') · Hi Branding</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:400,500,600,400i,500i|plus-jakarta-sans:400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#FDFBF7] font-sans text-[#211E1D]">
    <div class="intro-loader" id="intro-loader" aria-hidden="true">
        <div class="intro-loader-mark">hb</div>
        <span class="intro-loader-name">Hi Branding</span>
    </div>
    <a class="skip-link" href="#main-content">Saltar al contenido</a>
    <header class="site-header">
        <a class="brand" href="{{ route('home') }}" aria-label="Hi Branding, inicio">
            <span class="brand-monogram">hb</span>
            <span class="brand-name">Hi Branding</span>
        </a>
        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-nav" aria-label="Abrir menú">
            <span></span><span></span>
        </button>
        <nav class="primary-nav" id="primary-nav" aria-label="Navegación principal">
            <a href="{{ route('home') }}#philosophy">Propósito</a>
            <a href="{{ route('memberships.index') }}">Membresías</a>
            <a href="{{ route('location.index') }}">Ubicación</a>
            <a href="{{ route('community.index') }}">Comunidad</a>
        </nav>
        <a class="button button--small button--rust header-cta" href="{{ route('appointments.index') }}">Agendar Asesoría <span aria-hidden="true">↗</span></a>
    </header>

    @yield('content')

    <footer class="site-footer">
        <div class="footer-main">
            <a class="brand brand--light" href="{{ route('home') }}" aria-label="Hi Branding, inicio">
                <span class="brand-monogram">hb</span>
                <span class="brand-name">Hi Branding</span>
            </a>
            <p>Un lugar para<br>hacer que las cosas pasen.</p>
            <div class="footer-group"><span>Ecosistema</span><a href="{{ route('home') }}#philosophy">Propósito</a><a href="{{ route('memberships.index') }}">Membresías</a><a href="{{ route('location.index') }}">Ubicación</a></div>
            <div class="footer-group"><span>Club & Agenda</span><a href="{{ route('community.index') }}">Comunidad</a><a href="{{ route('events.index') }}">Eventos</a><a href="{{ route('appointments.index') }}">Asesorías</a></div>
            <div class="footer-group footer-group--inquiries"><span>Inquiries</span>
                <a href="mailto:hola@hibranding.com">hola@hibranding.com <span aria-hidden="true">↗</span></a>
            </div>
        </div>
        <div class="footer-meta"><span>Privacidad</span><span>© {{ now()->year }} Hi Branding. Todos los derechos reservados.</span><span>Términos</span></div>
    </footer>
    @stack('scripts')
</body>
</html>