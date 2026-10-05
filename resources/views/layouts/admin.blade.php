<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f8f4ee">
    <title>@yield('title', 'Panel de Administración') · Hi Branding Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:400,500,600,400i,500i|plus-jakarta-sans:400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#FDFBF7] font-sans text-[#211E1D]">
    <header class="admin-header">
        <a class="brand" href="{{ route('admin.dashboard') }}" aria-label="Hi Branding Admin, inicio">
            <span class="brand-monogram">hb</span>
            <span class="brand-name">Hi Branding <em>Admin</em></span>
        </a>
        <nav class="admin-nav" aria-label="Navegación de administración">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.events.index') }}" class="{{ request()->routeIs('admin.events.*') ? 'is-active' : '' }}">Eventos</a>
            <a href="{{ route('admin.bookings.index') }}" class="{{ request()->routeIs('admin.bookings.*') ? 'is-active' : '' }}">Reservas</a>
        </nav>
        <form method="POST" action="{{ route('admin.logout') }}" class="admin-logout-form">
            @csrf
            <button type="submit" class="button button--small button--outline">Cerrar sesión</button>
        </form>
    </header>

    <main class="admin-main">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>