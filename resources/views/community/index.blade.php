@extends('layouts.app')

@section('title', 'La comunidad')

@section('content')
<main id="main-content" class="community-page">
    <section class="community-hero">
        <div class="community-hero-copy"><p class="eyebrow">El club Hi · Fundadores & visionarios</p><h1 class="animate__animated animate__swing">Donde el Capital, la Estrategia y el Talento <em>se Sincronizan.</em></h1><p>Una comunidad curada y selectiva de más de 500 fundadores, líderes y expertos que comparten una visión ambiciosa y construyen en conjunto.</p><label class="member-search"><span aria-hidden="true">⌕</span><input type="search" data-member-search placeholder="Buscar miembros, empresas o expertise" aria-label="Buscar miembros"></label><div class="filter-list community-hero-filters" role="group" aria-label="Filtrar por industria"><button class="filter-button is-active" type="button" data-member-filter="all" aria-pressed="true">Todas</button><button class="filter-button" type="button" data-member-filter="Fintech & VC" aria-pressed="false">Fintech & VC</button><button class="filter-button" type="button" data-member-filter="AI & Deeptech" aria-pressed="false">AI & Deeptech</button><button class="filter-button" type="button" data-member-filter="Consumer & Creative" aria-pressed="false">Consumer & Creative</button></div></div>
    </section>

    <section class="metrics-band" aria-label="Hi en números"><div class="metrics-network" id="network-canvas" aria-hidden="true"></div><div class="metrics-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto my-12"><div><strong>500<span>+</span></strong><span>miembros activos</span></div><div><strong>120<span>+</span></strong><span>startups en la red</span></div><div><strong>$84<span>M USD</span></strong><span>capital levantado</span></div><div><strong>95<span>%</span></strong><span>conexiones por referidos</span></div></div></section>

    <section class="directory section-wrap">
        <div class="section-heading"><div><p class="eyebrow">El capital se construye en comunidad</p><h2 class="animate__animated animate__swing">Perfiles destacados <em>del club.</em></h2></div><span class="directory-total">{{ count($members) }} perfiles destacados · Ver todos ↗</span></div>
        <div class="directory-controls"><span class="directory-note">Perfiles verificados</span></div>
        <div class="member-grid" id="member-grid">
            @foreach($members as $member)
                <article class="member-card animate__animated animate__flipInY" style="animation-delay: {{ min(($loop->index % 8) * 70, 490) }}ms; animation-fill-mode: both" data-member-category="{{ $member['category'] }}" data-member-search="{{ strtolower($member['name'].' '.$member['company'].' '.$member['role'].' '.$member['category']) }}">
                    <div class="member-avatar member-avatar--{{ $member['tone'] }}">@if(!empty($member['photo_url']))<img src="{{ $member['photo_url'] }}" alt="Retrato de {{ $member['name'] }}" loading="lazy">@else<span>{{ $member['initials'] }}</span>@endif<span class="avatar-index">HI / {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span></div>
                    <div class="member-details"><p class="eyebrow">{{ $member['category'] }}</p><h3>{{ $member['name'] }}</h3><p>{{ $member['role'] }}</p><div class="member-bottom"><strong>{{ $member['company'] }}</strong><a href="mailto:{{ $member['email'] ?? 'hola@hibranding.com' }}?subject={{ urlencode('Conectar con '.$member['name']) }}" aria-label="Conectar Office con {{ $member['name'] }}">Conectar Office <span aria-hidden="true">↗</span></a></div></div>
                </article>
            @endforeach
        </div>
        <p class="filter-empty" id="filter-empty" hidden>No hay perfiles en esta categoría por ahora.</p>
        <div class="directory-bottom"><span>Esto es solo una pequeña parte de Hi.</span><a class="text-link" href="{{ route('appointments.index') }}">Conectar con la comunidad <span aria-hidden="true">↗</span></a></div>
    </section>
</main>
@endsection

@push('scripts')
    @vite('resources/js/p5-network.js')
@endpush