@extends('layouts.admin')

@section('title', 'Evento: ' . $event->title)

@section('content')
<div class="event-show animate__animated animate__fadeInUp">
    <header class="event-show-header">
        @if ($event->image)
            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="event-show-image">
        @else
            <div class="event-show-image" style="background: #4d413b; display: flex; align-items: center; justify-content: center; color: white;">
                <span>Sin imagen</span>
            </div>
        @endif
        <div class="event-show-overlay"></div>
    </header>

    <div class="event-show-content">
        <div class="event-show-meta">
            <span class="eyebrow">{{ $event->category }}</span>
            <span>{{ $event->starts_at->locale('es')->translatedFormat('l, j F Y') }}</span>
            <span>{{ $event->starts_at->format('H:i') }} hrs</span>
            <span>{{ $event->place }}</span>
        </div>

        <h1 class="event-show-title">{{ $event->title }}</h1>

        @if ($event->starts_at > now())
            <div class="countdown-timer" data-event-date="{{ $event->starts_at->toISOString() }}">
                <div class="countdown-item">
                    <span class="countdown-value" data-days>00</span>
                    <span class="countdown-label">Días</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-value" data-hours>00</span>
                    <span class="countdown-label">Horas</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-value" data-minutes>00</span>
                    <span class="countdown-label">Min</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-value" data-seconds>00</span>
                    <span class="countdown-label">Seg</span>
                </div>
            </div>
        @endif

        <div class="event-show-description">
            {!! nl2br(e($event->description)) !!}
        </div>

        <div class="bank-details-card">
            <h3>Datos de pago para transferencia</h3>
            <div class="bank-details-grid">
                <div class="bank-detail-item">
                    <span class="bank-detail-label">Banco</span>
                    <span class="bank-detail-value">{{ $event->bank_details['bank'] ?? '—' }}</span>
                </div>
                <div class="bank-detail-item">
                    <span class="bank-detail-label">Cuenta</span>
                    <span class="bank-detail-value">{{ $event->bank_details['account'] ?? '—' }}</span>
                </div>
                <div class="bank-detail-item">
                    <span class="bank-detail-label">Titular</span>
                    <span class="bank-detail-value">{{ $event->bank_details['holder'] ?? '—' }}</span>
                </div>
                <div class="bank-detail-item">
                    <span class="bank-detail-label">Cédula / RIF</span>
                    <span class="bank-detail-value">{{ $event->bank_details['document'] ?? '—' }}</span>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--line);">
            <a href="{{ route('admin.events.edit', $event) }}" class="button button--rust">Editar evento</a>
            <a href="{{ route('admin.bookings.index', ['event_id' => $event->id]) }}" class="button button--outline">Ver reservas ({{ $event->bookings->count() }})</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const timer = document.querySelector('[data-event-date]');
    if (!timer) return;

    const targetDate = new Date(timer.dataset.eventDate).getTime();

    const daysEl = timer.querySelector('[data-days]');
    const hoursEl = timer.querySelector('[data-hours]');
    const minutesEl = timer.querySelector('[data-minutes]');
    const secondsEl = timer.querySelector('[data-seconds]');

    const updateTimer = () => {
        const now = Date.now();
        const distance = targetDate - now;

        if (distance < 0) {
            timer.innerHTML = '<p class="eyebrow">El evento ha comenzado</p>';
            return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        daysEl.textContent = String(days).padStart(2, '0');
        hoursEl.textContent = String(hours).padStart(2, '0');
        minutesEl.textContent = String(minutes).padStart(2, '0');
        secondsEl.textContent = String(seconds).padStart(2, '0');
    };

    updateTimer();
    setInterval(updateTimer, 1000);
});
</script>
@endpush