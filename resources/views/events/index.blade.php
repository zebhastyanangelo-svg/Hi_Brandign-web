@extends('layouts.app')

@section('title', 'Encuentros Hi')

@section('content')
<main id="main-content" class="events-page">
    <section class="events-intro section-wrap"><div><p class="eyebrow">El calendario de Hi</p><h1>Las ideas también<br>necesitan <em>una mesa.</em></h1></div><p>Encuentros para aprender algo nuevo, compartir lo que estás haciendo y conocer a quienes están construyendo lo que sigue.</p></section>

    <section class="featured-event section-wrap">
        <div class="featured-event-image" role="img" aria-label="Cena íntima en un espacio de diseño contemporáneo"></div>
        <div class="featured-event-copy"><p class="eyebrow">Próximo encuentro · {{ $featuredEvent->starts_at->locale('es')->translatedFormat('j F') }}</p><span class="event-featured-tag">HI EVENTS · 001</span><h2>{{ $featuredEvent->title }}</h2><p>{{ $featuredEvent->description }}</p><div class="featured-event-meta"><span>{{ $featuredEvent->starts_at->locale('es')->translatedFormat('l') }}</span><span>{{ $featuredEvent->starts_at->format('H:i') }} hrs</span><span>{{ $featuredEvent->place }}</span></div><a class="button button--rust" href="mailto:hola@hibranding.com?subject={{ urlencode($featuredEvent->title) }}">Confirmar asistencia <span aria-hidden="true">↗</span></a></div>
    </section>

    <section class="events-list-section section-wrap">
        <div class="section-heading"><div><p class="eyebrow">Guarda la fecha</p><h2>Lo que viene <em>en Hi.</em></h2></div><span class="directory-total">OCTUBRE · 2026</span></div>
        <div class="events-list">
            @foreach($upcomingEvents as $event)
                <article class="event-row">
                    <div class="event-date"><strong>{{ $event->starts_at->format('d') }}</strong><span>{{ strtoupper($event->starts_at->locale('es')->translatedFormat('M')) }}</span></div>
                    <div class="event-description"><p class="eyebrow">{{ $event->category }}</p><h3>{{ $event->title }}</h3><p>{{ $event->description }}</p><div class="event-meta"><span>{{ $event->starts_at->locale('es')->translatedFormat('l') }}, {{ $event->starts_at->format('H:i') }}</span><span>{{ $event->place }}</span></div></div>
                    <a class="event-arrow" href="mailto:hola@hibranding.com?subject={{ urlencode($event->title) }}" aria-label="Más información sobre {{ $event->title }}">↗</a>
                </article>
            @endforeach
        </div>
        <div class="event-subscribe"><div><p class="eyebrow">Una buena invitación llega a tiempo</p><h2>Que no te lo <em>cuenten.</em></h2></div><a class="button button--rust" href="mailto:hola@hibranding.com?subject=Eventos%20Hi">Recibe noticias de Hi <span aria-hidden="true">↗</span></a></div>
    </section>
</main>
@endsection