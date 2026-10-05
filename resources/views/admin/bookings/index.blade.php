@extends('layouts.admin')

@section('title', 'Gestión de Reservas')

@section('content')
<div class="admin-bookings-page animate__animated animate__fadeInUp">
    <header class="admin-events-header">
        <div>
            <p class="eyebrow">Administración</p>
            <h1>Reservas y pagos</h1>
        </div>
    </header>

    <div class="admin-form" style="margin-bottom: 24px;">
        <form method="GET" action="{{ route('admin.bookings.index') }}" class="admin-form-body" style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 16px; align-items: end;">
            <div class="admin-form-group" style="margin: 0;">
                <label for="status">Filtrar por estado</label>
                <select id="status" name="status">
                    <option value="">Todos</option>
                    <option value="pendiente" {{ request('status') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="aprobado" {{ request('status') === 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                    <option value="rechazado" {{ request('status') === 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                </select>
            </div>

            <div class="admin-form-group" style="margin: 0;">
                <label for="event_id">Filtrar por evento</label>
                <select id="event_id" name="event_id">
                    <option value="">Todos los eventos</option>
                    @foreach ($events as $event)
                        <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>{{ $event->title }} ({{ $event->starts_at->format('d/m/Y') }})</option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="submit" class="button button--rust">Filtrar</button>
                <a href="{{ route('admin.bookings.index') }}" class="button button--outline">Limpiar</a>
            </div>
        </form>
    </div>

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Referencia</th>
                    <th>Evento</th>
                    <th>Asistentes</th>
                    <th>Monto</th>
                    <th>Estado</th>
                    <th>Ref. Pago</th>
                    <th>Fecha pago</th>
                    <th>Comprobante</th>
                    <th>Fecha reserva</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookings as $booking)
                    <tr>
                        <td><code>{{ $booking->reference }}</code></td>
                        <td>
                            <strong>{{ $booking->event->title }}</strong>
                            <br><small class="text-muted">{{ $booking->event->starts_at->format('d/m/Y') }}</small>
                        </td>
                        <td>{{ $booking->tickets_count }}</td>
                        <td>${{ number_format($booking->total_amount, 2) }}</td>
                        <td>
                            <span class="status-badge status-badge--{{ $booking->status }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td>{{ $booking->payment_reference ?? '—' }}</td>
                        <td>{{ $booking->payment_date?->format('d/m/Y') ?? '—' }}</td>
                        <td>
                            @if ($booking->proof_path)
                                <a href="{{ asset('storage/' . $booking->proof_path) }}" target="_blank" class="text-link">
                                    Ver comprobante <span aria-hidden="true">→</span>
                                </a>
                            @else
                                <span class="text-muted">Sin adjuntar</span>
                            @endif
                        </td>
                        <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="admin-actions">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="button button--small button--outline">Ver</a>

                                @if ($booking->status === 'pendiente')
                                    <form method="POST" action="{{ route('admin.bookings.approve', $booking) }}" class="admin-action-form" onsubmit="return confirm('¿Aprobar este pago? El asistente quedará inscrito oficialmente.')">
                                        @csrf
                                        <button type="submit" class="button button--small" style="background: #365b40; color: white; border-color: #365b40;">Aprobar</button>
                                    </form>

                                    <button type="button" class="button button--small button--outline reject-btn" data-booking-id="{{ $booking->id }}" style="color: #a43b2b; border-color: #e8c8c0;">Rechazar</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="admin-empty-state">
                            <span class="empty-icon">📋</span>
                            <h3>No hay reservas</h3>
                            <p>{{ $events->isEmpty() ? 'Crea un evento primero.' : 'No hay reservas con los filtros actuales.' }}</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $bookings->links() }}

    <!-- Reject Modal -->
    <dialog id="reject-modal" class="booking-dialog animate__animated animate__fadeInUp">
        <form method="POST" id="reject-form" class="booking-form">
            <button type="button" class="dialog-close" aria-label="Cerrar">×</button>

            <p class="eyebrow">Rechazar pago</p>
            <h2>Confirmar rechazo</h2>
            <p class="form-intro">El asistente será notificado y la reserva quedará marcada como rechazada.</p>

            <input type="hidden" name="booking_id" id="reject-booking-id">

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