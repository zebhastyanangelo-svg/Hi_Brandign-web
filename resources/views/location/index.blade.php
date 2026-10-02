@extends('layouts.app')

@section('title', 'Ubicación & sede central')

@section('content')
<main id="main-content" class="location-page section-wrap">
    <section class="location-heading"><div><p class="eyebrow">Ubicación & sede central</p><h1>Un espacio concebido para la inspiración y el <em>encuentro.</em></h1><p>Nuestra sede combina la arquitectura atemporal con la funcionalidad moderna, ofreciendo un refugio privado donde las ideas de marca cobran vida propia.</p></div><aside class="hours-note"><span aria-hidden="true">◷</span><div><strong>Horarios de Atención</strong><span>Lunes a Viernes: 9:00 - 19:00</span></div></aside></section>
    <section class="location-feature-grid">
        <article class="location-feature location-feature--studio"><div class="location-art"><div class="location-logo-wrap"><div id="badge-canvas" class="location-badge" aria-label="Hacemos hincapié en ti"></div><span class="location-logo">hb</span></div></div><div class="location-feature-copy"><p class="eyebrow">El estudio</p><h2>Arquitectura interior minimalista</h2><p>Diseñado para estimular la concentración, con luz natural abundante y zonas de co-creación exclusivas para nuestros socios.</p></div></article>
        <article class="location-feature location-feature--map"><div class="map-art"><div class="map-route map-route--one"></div><div class="map-route map-route--two"></div><div class="map-label"><span aria-hidden="true">⌖</span><div><strong>Hi Branding Headquarters</strong><span>Av. Vitacura 4980, Of. 402, Santiago</span></div></div><span class="map-pin" aria-hidden="true">✳</span></div><div class="location-feature-copy location-feature-copy--map"><div><h2>Visítanos en Santiago</h2><p>Agenda una cita previa para recibir atención personalizada de nuestros directores creativos.</p></div><a class="button button--rust" href="https://maps.google.com/?q=Av.+Vitacura+4980,+Santiago" target="_blank" rel="noreferrer">Cómo llegar <span aria-hidden="true">↗</span></a></div></article>
    </section>
    <section class="amenities-grid" aria-label="Servicios de la sede"><article><span aria-hidden="true">P</span><h2>Estacionamiento Privado</h2><p>Disponibilidad exclusiva para visitantes y socios con membresía activa en el edificio.</p></article><article><span aria-hidden="true">♧</span><h2>Café & Lounge Bar</h2><p>Un espacio distendido para networking y conversaciones de valor antes de cada sesión estratégica.</p></article><article><span aria-hidden="true">⌑</span><h2>Acceso Biométrico</h2><p>Máxima seguridad y privacidad garantizada para todas las marcas y directivos que nos visitan.</p></article></section>
</main>
@endsection

@push('scripts')
    @vite('resources/js/p5-badge.js')
@endpush