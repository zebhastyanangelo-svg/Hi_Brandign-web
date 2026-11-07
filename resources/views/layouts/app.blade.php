<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f8f4ee">
    <meta name="description" content="Hi Branding: espacios, comunidad y acompañamiento para quienes están construyendo lo que sigue.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Un lugar para lo que sigue') · Hi Branding</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:400,500,600,400i,500i|plus-jakarta-sans:400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#FDFBF7] font-sans text-[#211E1D]">
    <div class="intro-loader fixed inset-0 z-[9999] flex items-center justify-center bg-[#D1CBC2]" id="intro-loader" aria-hidden="true">
        <video autoplay muted playsinline class="max-w-[92vw] max-h-[90vh] w-auto h-auto object-contain block m-auto" id="intro-video">
            <source src="{{ asset('Vectorizar_isologo_con_impacto_b…_bb828913_1080p.mp4') }}" type="video/mp4">
        </video>
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
        <div class="header-actions">
            <a class="button button--small button--rust header-cta" href="{{ route('appointments.index') }}">Agendar Asesoría <span aria-hidden="true">↗</span></a>

            <div class="admin-access" id="admin-access">
                @if (auth('admin')->check())
                    <div class="admin-dropdown" id="admin-dropdown">
                        <button type="button" class="admin-avatar admin-avatar--active" aria-expanded="false" aria-haspopup="true" aria-label="Panel de administración" id="adminDropdownToggle">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span class="admin-badge" aria-label="Sesión activa"></span>
                        </button>
                        <div class="admin-dropdown-menu" id="adminDropdownMenu" role="menu" hidden>
                            <a href="{{ route('admin.dashboard') }}" class="admin-dropdown-item" role="menuitem">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="14" width="7" height="7"></rect>
                                    <rect x="3" y="14" width="7" height="7"></rect>
                                </svg>
                                <span>Panel de Control</span>
                            </a>
                            <a href="{{ route('admin.events.index') }}" class="admin-dropdown-item" role="menuitem">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <span>Gestión de Eventos</span>
                            </a>
                            <a href="{{ route('admin.bookings.index') }}" class="admin-dropdown-item" role="menuitem">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <span>Reservas y Pagos</span>
                            </a>
                            <hr class="admin-dropdown-divider">
                            <form method="POST" action="{{ route('admin.logout') }}" class="admin-dropdown-form">
                                @csrf
                                <button type="submit" class="admin-dropdown-item admin-dropdown-item--danger" role="menuitem">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16 17 21 12 16 7"></polyline>
                                        <line x1="21" y1="12" x2="9" y2="12"></line>
                                    </svg>
                                    <span>Cerrar Sesión</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('admin.login') }}" class="admin-avatar" data-open-admin-login aria-label="Acceso Privado Dirección" title="Acceso Privado Dirección">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
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
    @if (!auth('admin')->check() && !request()->routeIs('admin.login'))
        <dialog id="admin-login-modal" class="booking-dialog admin-login-dialog" aria-label="Acceso Privado Dirección">
            <button type="button" class="dialog-close" data-close-admin-login aria-label="Cerrar">×</button>
            @include('partials.admin-login-card', ['loginContext' => 'modal'])
        </dialog>
    @endif

    @stack('scripts')
</body>
</html>