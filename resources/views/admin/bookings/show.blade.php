@extends('layouts.admin')

@section('title', 'Reserva: ' . $booking->reference)

@section('content')
<div class="booking-detail animate__animated animate__fadeInUp">
    <header class="booking-detail-header">
        <h2>Reserva {{ $booking->reference }}</h2>
        <a href="{{ route('admin.bookings.index') }}" class="button button--small button--outline">Volver</a>
    </header>

    <div class="booking-detail-body">
        <div class="booking-detail-grid">
            <section class="booking-detail-section">
                <h3>Información de la reserva</h3>
                <div class="booking-detail-row">
                    <span class="booking-detail-label">Referencia</span>
                    <span class="booking-detail-value">{{ $booking->reference }}</span>
                </div>
                <div class="booking-detail-row">
                    <span class="booking-detail-label">Evento</span>
                    <span class="booking-detail-value">{{ $booking->event->title }}</span>
                </div>
                <div class="booking-detail-row">
                    <span class="booking-detail-label">Fecha del evento</span>
                    <span class="booking-detail-value">{{ $booking->event->starts_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="booking-detail-row">
                    <span class="booking-detail-label">Lugar</span>
                    <span class="booking-detail-value">{{ $booking->event->place }}</span>
                </div>
                <div class="booking-detail-row">
                    <span class="booking-detail-label">Entradas</span>
                    <span class="booking-detail-value">{{ $booking->tickets_count }}</span>
                </div>
                <div class="booking-detail-row">
                    <span class="booking-detail-label">Monto total</span>
                    <span class="booking-detail-value">${{ number_format($booking->total_amount, 2) }}</span>
                </div>
                <div class="booking-detail-row">
                    <span class="booking-detail-label">Estado</span>
                    <span class="booking-detail-value">
                        <span class="status-badge status-badge--{{ $booking->status }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </span>
                </div>
                <div class="booking-detail-row">
                    <span class="booking-detail-label">Fecha de reserva</span>
                    <span class="booking-detail-value">{{ $booking->created_at->format('d/m/Y H:i') }}</span>
                </div>
                @if ($booking->approved_at)
                    <div class="booking-detail-row">
                        <span class="booking-detail-label">Aprobado el</span>
                        <span class="booking-detail-value">{{ $booking->approved_at->format('d/m/Y H:i') }}</span>
                    </div>
                @endif
            </section>

            <section class="booking-detail-section">
                <h3>Datos del pago</h3>
                <div class="booking-detail-row">
                    <span class="booking-detail-label">Referencia de pago</span>
                    <span class="booking-detail-value">{{ $booking->payment_reference ?? '—' }}</span>
                </div>
                <div class="booking-detail-row">
                    <span class="booking-detail-label">Fecha de pago</span>
                    <span class="booking-detail-value">{{ $booking->payment_date?->format('d/m/Y') ?? '—' }}</span>
                </div>
                <div class="booking-detail-row">
                    <span class="booking-detail-label">Comprobante</span>
                    <span class="booking-detail-value">
                        @if ($booking->proof_path)
                            <a href="{{ asset('storage/' . $booking->proof_path) }}" target="_blank" class="text-link">
                                Ver comprobante <span aria-hidden="true">→</span>
                            </a>
                            @if (str_ends_with($booking->proof_path, '.pdf'))
                                <iframe class="proof-preview" src="{{ asset('storage/' . $booking->proof_path) }}" frameborder="0"></iframe>
                            @else
                                <img class="proof-preview" src="{{ asset('storage/' . $booking->proof_path) }}" alt="Comprobante de pago">
                            @endif
                        @else
                            Sin comprobante
                        @endif
                    </span>
                </div>
                @if ($booking->admin_notes)
                    <div class="booking-detail-row">
                        <span class="booking-detail-label">Notas del admin</span>
                        <span class="booking-detail-value">{{ $booking->admin_notes }}</span>
                    </div>
                @endif
            </section>
        </div>

        <section class="attendees-list">
            <h3 style="margin: 0 0 16px; font-size: 16px; color: var(--rust);">Asistentes ({{ $booking->attendees->count() }})</h3>
            @forelse ($booking->attendees as $attendee)
                <article class="attendee-card">
                    <div class="attendee-info">
                        <span class="attendee-name">{{ $attendee->full_name }}</span>
                        <div class="attendee-meta">
                            <span>{{ $attendee->document }}</span>
                            <span>{{ $attendee->email }}</span>
                        </div>
                    </div>
                </article>
            @empty
                <p class="text-muted">No hay asistentes registrados.</p>
            @endforelse
        </section>

        @if ($booking->status === 'pendiente')
            <div class="booking-actions">
                <form method="POST" action="{{ route('admin.bookings.approve', $booking) }}" onsubmit="return confirm('¿Aprobar este pago? El asistente quedará inscrito oficialmente.')">
                    @csrf
                    <button type="submit" class="button button--rust" style="background: #365b40; border-color: #365b40;">
                        <span aria-hidden="true">✓</span> Aprobar pago
                    </button>
                </form>

                <button type="button" class="button button--outline reject-btn" data-booking-id="{{ $booking->id }}" style="color: #a43b2b; border-color: #e8c8c0;">
                    <span aria-hidden="true">✕</span> Rechazar
                </button>
            </div>
        @endif
    </div>

    <!-- Reject Modal -->
    <dialog id="reject-modal" class="booking-dialog animate__animated animate__fadeInUp">
        <form method="POST" id="reject-form" class="booking-form">
            <button type="button" class="dialog-close" aria-label="Cerrar">×</button>

            <p class="eyebrow">Rechazar pago</p>
            <h2>Confirmar rechazo</h2>
            <p class="form-intro">El asistente será notificado y la reserva quedará marcada como rechazada.</p>

            <input type="hidden" name="booking_id" id="reject-booking-id" value="{{ $booking->id }}">

            <label>
                Motivo (opcional)
                <textarea name="admin_notes" rows="3" placeholder="Ej. Comprobante ilegible, monto incorrecto, datos no coinciden..."></textarea>
            </label>

            <div class="admin-form-actions" style="justify-content: flex-end; border-top: 0; padding-top: 0; margin-top: 8px;">
                <button type="button" class="button button--outline" id="reject-cancel">Cancelar</button>
                <button type="submit" class="button button--rust" style="background: #a43b2b; border-color: #a43b2b;">Rechazar</button>
            </div>
        </form>
    </dialog>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('reject-modal');
    const form = document.getElementById('reject-form');
    const bookingIdInput = document.getElementById('reject-booking-id');
    const cancelBtn = document.getElementById('reject-cancel');

    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            bookingIdInput.value = btn.dataset.bookingId;
            modal.showModal();
        });
    });

    cancelBtn?.addEventListener('click', () => modal.close());
    modal?.addEventListener('click', (e) => { if (e.target === modal) modal.close(); });

    form?.addEventListener('submit', (e) => {
        e.preventDefault();
        const bookingId = bookingIdInput.value;
        const formData = new FormData(form);

        fetch(`{{ route('admin.bookings.reject', ':id') }}`.replace(':id', bookingId), {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error al rechazar');
            }
        })
        .catch(() => alert('Error de conexión'));
    });
});
</script>
@endpush