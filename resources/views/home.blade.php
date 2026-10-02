@extends('layouts.app')

@section('title', 'Propósito e identidad')

@section('content')
<main id="main-content" class="home-page">
    <section class="story-hero" data-story-hero aria-labelledby="hero-title">
        <div class="story-hero-image"><img src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=2400&q=90" alt="" fetchpriority="high"></div>
        <div class="story-hero-shade" aria-hidden="true"></div>
        <div class="story-hero-canvas" id="hero-canvas" aria-hidden="true"></div>
        <div class="story-hero-inner">
            <p class="eyebrow eyebrow--light"><span class="eyebrow-dot"></span> Propósito & identidad</p>
            <h1 class="font-serif animate__animated animate__swing" id="hero-title">Donde el Talento Converge y el Futuro <em>se Construye.</em></h1>
            <p class="story-hero-copy">Hacemos hincapié en ti. En el valor de tu visión y en el lugar que merece para crecer.</p>
            <div class="landing-actions flex items-center"><a class="button button--rust" href="{{ route('memberships.index') }}">Descubrir Membresías <span aria-hidden="true">↗</span></a><a class="text-link text-link--light" href="#philosophy">Nuestra Filosofía <span aria-hidden="true">↓</span></a></div>
        </div>
        <div class="story-hero-caption"><span>CASA HI · SANTIAGO</span><span>ESPACIOS PARA CONECTAR</span></div>
        <div class="space-finder story-space-finder"><label class="finder-search"><span aria-hidden="true">⌕</span><input type="search" data-space-search placeholder="¿Qué espacio necesitas?" aria-label="Buscar espacios"></label><div class="finder-filters" role="group" aria-label="Filtrar espacios"><button class="filter-button is-active" type="button" data-space-filter="all" aria-pressed="true">Todos</button><button class="filter-button" type="button" data-space-filter="Eventos" aria-pressed="false">Eventos & grupos</button><button class="filter-button" type="button" data-space-filter="Coworking" aria-pressed="false">Coworking</button><button class="filter-button" type="button" data-space-filter="Salud" aria-pressed="false">Salud & telemedicina</button></div><a class="finder-submit" href="{{ route('memberships.index') }}">Explorar espacios <span aria-hidden="true">↗</span></a></div>
    </section>

    <section class="home-memberships section-wrap" id="spaces">
        <div class="section-heading"><div><p class="eyebrow">Espacios diseñados para la excelencia</p><h2 class="animate__animated animate__swing">Encuentra tu forma <em>de crecer.</em></h2></div><p class="section-heading-note">Diseño que inspira, tecnología que acompaña y una comunidad hecha para conectar.</p></div>
        <div class="space-card-grid grid">
            @foreach($memberships as $membership)
                <article class="space-card" data-space-card data-space-category="{{ $loop->first ? 'Eventos' : ($loop->last ? 'Salud' : 'Coworking') }}" data-space-name="{{ strtolower($membership->name.' '.$membership->description) }}">
                    <a class="space-card-photo space-card-photo--{{ $loop->iteration }}" href="{{ route('memberships.index') }}" aria-label="Conocer {{ $membership->name }}"><span>{{ $membership->capacity ?? ($loop->first ? 'Capacidad 25–30' : ($loop->last ? 'Capacidad 12' : 'Flexible')) }}</span></a>
                    <div class="space-card-content"><p class="eyebrow">{{ $loop->first ? 'Eventos & grupo' : ($loop->last ? 'Salud & telemedicina' : 'Coworking & networking') }}</p><h3>{{ $membership->name }}</h3><p>{{ $membership->description }}</p><ul>@foreach(array_slice($membership->features, 0, 3) as $feature)<li><span aria-hidden="true">✳</span>{{ $feature }}</li>@endforeach</ul><div class="space-card-price"><span>Inversión desde</span><strong>${{ number_format($membership->price) }} <small>/ mes</small></strong></div><a class="button {{ $membership->featured ? 'button--rust' : 'button--outline' }}" href="{{ route('memberships.index') }}">Consultar Membresía <span aria-hidden="true">↗</span></a></div>
                </article>
            @endforeach
        </div>
        <p class="finder-empty" data-space-empty hidden>No encontramos espacios con esos criterios.</p>
        <div class="membership-callout"><span class="callout-icon" aria-hidden="true">✳</span><div><h3>¿Necesitas una membresía a tu medida?</h3><p>Nuestros asesores personalizan cada espacio de acuerdo con los requerimientos específicos de tu marca.</p></div><a class="button button--outline" href="{{ route('appointments.index') }}">Hablar con un asesor <span aria-hidden="true">↗</span></a></div>
    </section>

    <section class="philosophy-section" id="philosophy">
        <div class="philosophy-intro"><div><p class="eyebrow">Propósito & identidad</p><h2 class="animate__animated animate__swing">Hacemos hincapié en <em>ti</em> y en la esencia de tu marca.</h2><p>Transformamos visiones personales en ecosistemas de marca inolvidables. Diseño estratégico, quiet luxury y artesanía digital para quienes lideran con autenticidad.</p><div class="landing-actions"><a class="button button--rust" href="{{ route('memberships.index') }}">Descubrir Membresías <span aria-hidden="true">↗</span></a><a class="text-link" href="#values">Nuestra Filosofía <span aria-hidden="true">↓</span></a></div></div><div class="brand-art"><div class="brand-art-logo"><div class="brand-art-orbit" id="badge-canvas" aria-label="Hacemos hincapié en ti"></div><span class="brand-art-hb">hb</span><span class="brand-art-name"><em>Hi</em><br><strong>Branding</strong></span></div><p>“El diseño no es solo lo que se ve, sino cómo hace latir a tu audiencia.”</p></div></div>
        <div class="value-grid" id="values"><article><span>01</span><h3>Identidad Raíz</h3><p>Desenterrar la verdad fundamental de tu proyecto para construir una narrativa visual coherente y magnética.</p><a href="{{ route('appointments.index') }}">Estrategia <span aria-hidden="true">→</span></a></article><article><span>02</span><h3>Quiet Luxury</h3><p>Estética depurada que prioriza el espacio, la tipografía de autor y el refinamiento sobre el ruido visual.</p><a href="{{ route('appointments.index') }}">Diseño <span aria-hidden="true">→</span></a></article><article><span>03</span><h3>Ecosistema Vivo</h3><p>Red de apoyo, alianzas estratégicas y mentorías continuas para escalar tu marca con elegancia.</p><a href="{{ route('community.index') }}">Comunidad <span aria-hidden="true">→</span></a></article></div>
    </section>

    <section class="allies-section"><div class="allies-heading"><div><p class="eyebrow">Red global</p><h2 class="animate__animated animate__swing">Startups Aliadas & <em>Partners.</em></h2></div><p>Colaboramos con fundadores visionarios y marcas independientes que redefinen los estándares de sus industrias.</p></div><div class="allies-logos"><span>Atelier Noir</span><span>Vél Haut</span><span>Solaria Studio</span><span>Komorebi</span></div></section>
</main>
@endsection

@push('scripts')
    @vite(['resources/js/p5-hero.js', 'resources/js/p5-badge.js'])
@endpush