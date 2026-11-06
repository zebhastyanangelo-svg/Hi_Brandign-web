<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f8f4ee">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel de Control') · Hi Branding Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:400,500,600,400i,500i|plus-jakarta-sans:400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#FDFBF7] font-sans text-[#211E1D]">
@php
    $adminSidebarLinks = [
        [
            'label' => 'Panel de Control',
            'route' => route('admin.dashboard'),
            'active' => request()->routeIs('admin.dashboard'),
            'icon' => '<rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect>',
        ],
        [
            'label' => 'Gestión de Eventos',
            'route' => route('admin.events.index'),
            'active' => request()->routeIs('admin.events.*'),
            'icon' => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line>',
        ],
        [
            'label' => 'Clientes & Audiencia',
            'route' => route('admin.clients.index'),
            'active' => request()->routeIs('admin.clients.*'),
            'icon' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>',
        ],
        [
            'label' => 'Ingresos',
            'route' => route('admin.incomes.index'),
            'active' => request()->routeIs('admin.incomes.*'),
            'icon' => '<line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>',
        ],
        [
            'label' => 'Métodos de Pago',
            'route' => route('admin.payment-methods.index'),
            'active' => request()->routeIs('admin.payment-methods.*'),
            'icon' => '<rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line>',
        ],
        [
            'label' => 'Configuración',
            'route' => route('admin.settings.edit'),
            'active' => request()->routeIs('admin.settings.*'),
            'icon' => '<circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>',
        ],
    ];
@endphp
<div class="admin-shell">
    <aside class="admin-sidebar">
        <a class="brand admin-sidebar-brand" href="{{ route('admin.dashboard') }}" aria-label="Panel Directivo Hi Branding">
            <span class="brand-monogram">hb</span>
            <span class="brand-name">Hi Branding <em>Panel Directivo</em></span>
        </a>

        <nav class="admin-sidebar-nav" aria-label="Navegación del panel directivo">
            @foreach ($adminSidebarLinks as $link)
                <a href="{{ $link['route'] }}" class="admin-sidebar-link {{ $link['active'] ? 'is-active' : '' }}" {{ $link['active'] ? 'aria-current="page"' : '' }}>
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $link['icon'] !!}</svg>
                    <span>{{ $link['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="admin-sidebar-footer">
            <div class="admin-sidebar-user">
                <span class="admin-sidebar-avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr(auth('admin')->user()->name, 0, 1)) }}</span>
                <span class="admin-sidebar-user-info">
                    <strong>{{ auth('admin')->user()->name }}</strong>
                    <small>{{ auth('admin')->user()->email }}</small>
                </span>
            </div>
            <a class="admin-sidebar-site" href="{{ route('home') }}">Ver sitio público <span aria-hidden="true">↗</span></a>
            <form method="POST" action="{{ route('admin.logout') }}" class="admin-logout-form">
                @csrf
                <button type="submit" class="button button--small button--outline">
                    <span aria-hidden="true">←</span> Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    <div class="admin-body">
        <header class="admin-topbar">
            <p class="admin-topbar-context">Panel Directivo <span aria-hidden="true">/</span> @yield('title', 'Panel de Control')</p>
        </header>

        <main class="admin-main">
            @if (session('success'))
                <div class="admin-flash admin-flash--success animate__animated animate__fadeInUp animate__faster" role="status">
                    <span aria-hidden="true">✓</span> {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="admin-flash admin-flash--error animate__animated animate__fadeInUp animate__faster" role="alert">
                    <span aria-hidden="true">!</span> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
