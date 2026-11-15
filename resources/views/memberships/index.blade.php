@extends('layouts.app')

@section('title', 'Membresías exclusivas')

@section('content')
<main id="main-content" class="memberships-page">
    <section class="memberships-heading section-wrap"><div><p class="eyebrow">Membresías exclusivas</p><h1>Espacios diseñados para <em>tu evolución.</em></h1></div><p>Selecciona el entorno ideal que se alinea con tu propósito profesional y personal. Hacemos hincapié en ti.</p></section>
    <section class="plan-grid section-wrap" aria-label="Membresías disponibles">
        @foreach($memberships as $membership)
            @php
                $planNumber = str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT);
                $planCategoryLabel = match ($loop->iteration) {
                    1 => 'Acompañamiento',
                    2 => 'Eventos & reuniones',
                    default => 'Productos & experiencias',
                };
            @endphp
            <article class="plan-card">
                <div class="plan-photo plan-photo--{{ $loop->iteration }}"><span>Membresía {{ $planNumber }}</span></div>
                <div class="plan-card-body"><p class="eyebrow">{{ $planCategoryLabel }}</p><h2>{{ $membership->name }}</h2><p>{{ $membership->description }}</p><ul>@foreach($membership->features as $feature)<li><span aria-hidden="true">✳</span>{{ $feature }}</li>@endforeach</ul><a class="button button--rust" href="{{ route('memberships.checkout', $membership->slug) }}">Suscribirme ahora <span aria-hidden="true">↗</span></a></div>
            </article>
        @endforeach
    </section>
    <section class="membership-callout section-wrap"><span class="callout-icon" aria-hidden="true">✳</span><div><h3>¿Necesitas una membresía a tu medida?</h3><p>Nuestros asesores personalizan cada espacio de acuerdo con los requerimientos específicos de tu marca.</p></div><a class="button button--outline" href="{{ route('appointments.index') }}">Hablar con un asesor <span aria-hidden="true">↗</span></a></section>
</main>
@endsection