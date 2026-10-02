@extends('layouts.app')

@section('title', 'Tu espacio')

@section('content')
<main id="main-content" class="page-shell appointments-page">
    <div class="page-intro">
        <div><p class="eyebrow">Gestión de citas</p><h1>Tu espacio de asesoría <em>personal.</em></h1><p class="appointments-lead">Administra tus encuentros, revisa sesiones o agenda nuevas asesorías para potenciar tu marca personal con un enfoque exclusivo e individual.</p></div>
        <button class="button button--rust" type="button" data-open-booking>Agendar nueva cita <span aria-hidden="true">+</span></button>
    </div>

    @if(session('status'))<div class="notice" role="status">{{ session('status') }}</div>@endif

    <div class="dashboard-grid">
        <section class="dashboard-main" aria-labelledby="upcoming-title">
            <div class="list-heading"><div><h2 id="upcoming-title">Próximas Sesiones</h2></div><span class="count-badge">{{ $appointments->count() }} Activas</span></div>
            @forelse($appointments as $appointment)
                <article class="appointment-row">
                    <div class="appointment-date"><strong>{{ $appointment->scheduled_at->format('d') }}</strong><span>{{ strtoupper($appointment->scheduled_at->locale('es')->translatedFormat('M')) }}</span></div>
                    <div class="appointment-info"><p class="eyebrow">{{ $appointment->meeting_type === 'video' ? 'Videollamada' : 'En Casa Hi' }} · {{ $appointment->scheduled_at->format('H:i') }}</p><h3>{{ $appointment->focus }}</h3><p>Sesión para {{ $appointment->name }}</p></div>
                    <span class="status-tag">Confirmación pendiente</span>
                </article>
            @empty
                <div class="empty-state"><span class="empty-mark" aria-hidden="true">✳</span><div><h3>Aquí empieza algo bueno.</h3><p>Todavía no tienes sesiones. Cuéntanos qué estás construyendo y encontremos el espacio que necesitas.</p><button class="text-link" type="button" data-open-booking>Agendar mi primera cita <span aria-hidden="true">↗</span></button></div></div>
            @endforelse

            <div class="list-heading history-heading"><div><p class="eyebrow">Todo lo que ya empezó</p><h2>Sesiones recientes</h2></div></div>
            @forelse($history as $appointment)
                <article class="history-row"><span class="history-check" aria-hidden="true">✓</span><div><h3>{{ $appointment->focus }}</h3><p>{{ $appointment->scheduled_at->locale('es')->translatedFormat('j \d\e F, Y') }} · {{ $appointment->meeting_type === 'video' ? 'Videollamada' : 'En Casa Hi' }}</p></div><span class="history-status">Realizada</span></article>
            @empty
                <div class="history-empty">Aún no hay sesiones anteriores. Las conversaciones que empiezan aquí pueden llegar muy lejos.</div>
            @endforelse
        </section>

        <aside class="advisor-column" aria-label="Tu asesor y detalles">
            <h2 class="advisor-heading">Tu Asesor Asignado</h2>
            <article class="advisor-card"><div class="advisor-image" role="img" aria-label="Asesora de Hi Branding"></div><div class="advisor-body"><h3>Valeria Sotomayor</h3><p>Directora Creativa & Estratega</p><blockquote>“El verdadero branding no se trata de cómo te ves, sino de cómo haces sentir a quienes confían en ti.”</blockquote><div class="advisor-availability"><span>Sesiones Disponibles</span><strong>3 Restantes</strong></div></div></article>
            <div class="office-note"><span class="office-pin" aria-hidden="true">♧</span><div><p class="eyebrow">Consejo para tu cita</p><p>Ten listos tus objetivos actuales y los canales digitales donde interactúas con tu audiencia para aprovechar al máximo cada minuto de asesoría.</p></div></div>
            <p class="privacy-note">Tu información se trata con cuidado. Solo la usamos para hacer esta conversación mejor.</p>
        </aside>
    </div>

    <dialog class="booking-dialog" id="booking-dialog" aria-labelledby="booking-title">
        <form class="booking-form" action="{{ route('appointments.store') }}" method="post">
            @csrf
            <button class="dialog-close" type="button" aria-label="Cerrar formulario" data-close-booking>×</button>
            <p class="eyebrow">Hablemos de lo que sigue</p><h2 id="booking-title">Agendemos <em>un encuentro.</em></h2>
            <p class="form-intro">Cuéntanos un poco sobre ti y nos pondremos en contacto para confirmar.</p>
            <label>Nombre completo<input name="name" type="text" value="{{ old('name') }}" autocomplete="name" required maxlength="120">@error('name')<span class="field-error">{{ $message }}</span>@enderror</label>
            <label>Correo electrónico<input name="email" type="email" value="{{ old('email') }}" autocomplete="email" required maxlength="160">@error('email')<span class="field-error">{{ $message }}</span>@enderror</label>
            <label>¿Qué te gustaría conversar?<select name="focus" required><option value="" disabled selected>Elige un tema</option><option>Conocer los espacios</option><option>Encontrar mi membresía</option><option>Crecer mi comunidad</option><option>Otro proyecto</option></select>@error('focus')<span class="field-error">{{ $message }}</span>@enderror</label>
            <div class="form-row"><label>Fecha y hora<input name="scheduled_at" type="datetime-local" min="{{ now()->addHour()->format('Y-m-d\TH:i') }}" value="{{ old('scheduled_at') }}" required>@error('scheduled_at')<span class="field-error">{{ $message }}</span>@enderror</label><label>Formato<select name="meeting_type" required><option value="in-person">En Casa Hi</option><option value="video">Videollamada</option></select></label></div>
            <label>Algo más que quieras compartir <span class="optional">Opcional</span><textarea name="notes" rows="3" maxlength="1000">{{ old('notes') }}</textarea></label>
            <button class="button button--rust" type="submit">Solicitar cita <span aria-hidden="true">↗</span></button>
        </form>
    </dialog>
</main>
@endsection