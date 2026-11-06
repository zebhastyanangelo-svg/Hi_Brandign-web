@extends('layouts.admin')

@section('title', 'Guía Editorial')

@section('content')
<div class="admin-dashboard animate__animated animate__fadeInUp animate__faster">
    <header class="admin-dashboard-header">
        <div>
            <p class="eyebrow">Marca & Contenido</p>
            <h1>Guía <em>Editorial</em></h1>
        </div>
    </header>

    <div class="guide-grid">
        <section class="directivo-module animate__animated animate__fadeInUp animate__faster">
            <header class="directivo-module-head">
                <div>
                    <p class="module-index">Capítulo 01</p>
                    <h2>Voz de marca</h2>
                </div>
            </header>
            <div class="guide-body">
                <p class="guide-lead">Hablamos con la calma de quien no necesita demostrar nada: <em>quiet luxury</em> en cada frase.</p>
                <ul class="guide-list">
                    <li><strong>Cercano, no informal.</strong> Tuteamos siempre, sin jerga corporativa.</li>
                    <li><strong>Positivo, no eufórico.</strong> Celebramos logros sin signos de exclamación innecesarios.</li>
                    <li><strong>Hacemos hincapié en <em>ti</em>.</strong> El socio es siempre el protagonista de la historia.</li>
                    <li><strong>Elegante por restraint.</strong> Frases cortas, adjetivos precisos, cero superlativos vacíos.</li>
                </ul>
                <p class="guide-quote">“El diseño no es solo lo que se ve, sino cómo hace latir a la audiencia.”</p>
            </div>
        </section>

        <section class="directivo-module animate__animated animate__fadeInUp animate__faster">
            <header class="directivo-module-head">
                <div>
                    <p class="module-index">Capítulo 02</p>
                    <h2>Formatos & piezas</h2>
                </div>
            </header>
            <div class="guide-body">
                <ul class="guide-list">
                    <li><strong>Convocatorias de eventos.</strong> Título serif + fecha + lugar + un beneficio concreto.</li>
                    <li><strong>Membresías.</strong> Precios siempre con “Inversión desde”; nunca “precio” ni “costo”.</li>
                    <li><strong>Notas de comunidad.</strong> Nombre, organización y un logro medible del socio.</li>
                    <li><strong>Comunicados directivos.</strong> Protocolo sobrio: fondo tinta, tipografía Cormorant, badge terracota.</li>
                </ul>
            </div>
        </section>

        <section class="directivo-module animate__animated animate__fadeInUp animate__faster">
            <header class="directivo-module-head">
                <div>
                    <p class="module-index">Capítulo 03</p>
                    <h2>Calendario de publicación</h2>
                </div>
            </header>
            <div class="guide-body">
                <ul class="guide-list">
                    <li><strong>Lunes —</strong> Agenda semanal del club (eventos y horarios de salas).</li>
                    <li><strong>Miércoles —</strong> Nota de comunidad: un socio, un logro.</li>
                    <li><strong>Viernes —</strong> Convocatoria del evento destacado con boletín de cierre.</li>
                    <li><strong>Mensual —</strong> Reporte directivo de ingresos, membresías y ocupación.</li>
                </ul>
            </div>
        </section>

        <section class="directivo-module animate__animated animate__fadeInUp animate__faster">
            <header class="directivo-module-head">
                <div>
                    <p class="module-index">Capítulo 04</p>
                    <h2>Checklist antes de publicar</h2>
                </div>
            </header>
            <div class="guide-body">
                <ul class="guide-checklist">
                    <li>¿El titular abre con beneficio y cierra con elegancia?</li>
                    <li>¿Usamos paleta terracota <span class="swatch swatch--rust" aria-hidden="true"></span> o crema <span class="swatch swatch--cream" aria-hidden="true"></span> en acentos?</li>
                    <li>¿Fechas, precios y cupos están verificados en el panel?</li>
                    <li>¿La imagen transmite luz natural y materiales nobles?</li>
                    <li>¿Revisamos ortografía y traducciones en dos idiomas?</li>
                </ul>
            </div>
        </section>
    </div>
</div>
@endsection
