@extends('layouts.admin')

@section('title', 'Ingresos')

@section('content')
<div class="admin-page admin-page--incomes">
    <header class="admin-page-header">
        <div>
            <p class="eyebrow">Ingresos del club</p>
            <h1>Ingresos & <em>Pagos</em></h1>
            <p class="admin-page-lead">
                Ingresos generados por membresías y eventos. Tasa BCV de hoy:
                <strong>Bs. {{ number_format($bcvRate, 2, ',', '.') }} / USD</strong>
            </p>
        </div>
        <a href="{{ route('admin.reports.payments') }}" class="button button--small button--outline">
            <span aria-hidden="true">↓</span> Descargar reporte CSV
        </a>
    </header>

    <div class="income-kpi-grid">
        <article class="income-kpi income-kpi--total">
            <span class="kpi-label">Ingresos totales</span>
            <span class="kpi-value">${{ number_format($totals['total'], 2) }}</span>
            <span class="kpi-hint">Membresías + eventos aprobados</span>
        </article>
        <article class="income-kpi">
            <span class="kpi-label">Por membresías</span>
            <span class="kpi-value">${{ number_format($totals['memberships'], 2) }}</span>
            <span class="kpi-hint">Suscripciones confirmadas</span>
        </article>
        <article class="income-kpi">
            <span class="kpi-label">Por eventos</span>
            <span class="kpi-value">${{ number_format($totals['events'], 2) }}</span>
            <span class="kpi-hint">Reservas de eventos aprobadas</span>
        </article>
        <article class="income-kpi income-kpi--pending">
            <span class="kpi-label">Pagos por aprobar</span>
            <span class="kpi-value">{{ $totals['pending_count'] }}</span>
            <span class="kpi-hint">Notificaciones y reservas pendientes</span>
        </article>
    </div>

    <nav class="admin-tabs" aria-label="Secciones de ingresos">
        <a href="{{ route('admin.incomes.index', ['tab' => 'historial'] + $filters) }}"
           class="admin-tab {{ $tab === 'historial' ? 'is-active' : '' }}">
            Historial de pagos aprobados
        </a>
        <a href="{{ route('admin.incomes.index', ['tab' => 'pendientes'] + $filters) }}"
           class="admin-tab {{ $tab === 'pendientes' ? 'is-active' : '' }}">
            Pagos por aprobar
            @if ($totals['pending_count'] > 0)
                <span class="admin-tab-count">{{ $totals['pending_count'] }}</span>
            @endif
        </a>
    </nav>

    @if ($tab === 'historial')
        <section class="income-panel" aria-labelledby="income-history-title">
            <header class="income-panel-head">
                <h2 id="income-history-title">Historial de pagos aprobados</h2>
                <p class="admin-page-lead">Filtra por rango de fechas, método de pago y concepto.</p>
            </header>

            <form method="GET" action="{{ route('admin.incomes.index') }}" class="income-filters">
                <input type="hidden" name="tab" value="historial">
                <div class="income-filter-field">
                    <label for="filter-from">Desde</label>
                    <input type="date" id="filter-from" name="from" value="{{ $filters['from'] }}">
                </div>
                <div class="income-filter-field">
                    <label for="filter-to">Hasta</label>
                    <input type="date" id="filter-to" name="to" value="{{ $filters['to'] }}">
                </div>
                <div class="income-filter-field">
                    <label for="filter-method">Método de pago</label>
                    <select id="filter-method" name="method">
                        <option value="">Todos los métodos</option>
                        @foreach ($methods as $method)
                            <option value="{{ $method->name }}" {{ $filters['method'] === $method->name ? 'selected' : '' }}>{{ $method->name }}</option>
                        @endforeach
                        <option value="Transferencia" {{ $filters['method'] === 'Transferencia' ? 'selected' : '' }}>Transferencia (Eventos)</option>
                    </select>
                </div>
                <div class="income-filter-field">
                    <label for="filter-type">Concepto</label>
                    <select id="filter-type" name="type">
                        <option value="">Membresías y eventos</option>
                        <option value="membresia" {{ $filters['type'] === 'membresia' ? 'selected' : '' }}>Solo membresías</option>
                        <option value="evento" {{ $filters['type'] === 'evento' ? 'selected' : '' }}>Solo eventos</option>
                    </select>
                </div>
                <button type="submit" class="button button--small button--rust">Aplicar filtros</button>
                <a href="{{ route('admin.incomes.index', ['tab' => 'historial']) }}" class="button button--small button--outline">Limpiar</a>
            </form>

            @if ($payments->isEmpty())
                <div class="admin-empty-state">
                    <span class="empty-icon">📋</span>
                    <h3>Sin pagos aprobados</h3>
                    <p>Los pagos aprobados de membresías y eventos aparecerán aquí.</p>
                </div>
            @else
                <div class="admin-table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Referencia</th>
                                <th>Concepto</th>
                                <th>Cliente</th>
                                <th>Método</th>
                                <th>Monto USD</th>
                                <th>Monto VES (BCV)</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                <tr>
                                    <td>{{ $payment['paid_at']->format('d/m/Y') }}</td>
                                    <td><code>{{ $payment['reference'] }}</code></td>
                                    <td>
                                        {{ $payment['concept'] }}
                                        <small class="table-sub">{{ $payment['type_label'] }}</small>
                                    </td>
                                    <td>
                                        {{ $payment['client'] }}
                                        @if ($payment['client_email'])
                                            <small class="table-sub">{{ $payment['client_email'] }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $payment['method'] }}</td>
                                    <td><strong>${{ number_format($payment['amount'], 2) }}</strong></td>
                                    <td>Bs. {{ number_format($payment['amount'] * $bcvRate, 2, ',', '.') }}</td>
                                    <td>
                                        <span class="status-badge status-badge--pagado">Aprobado</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    @else
        <section class="income-panel" aria-labelledby="income-pending-title">
            <header class="income-panel-head">
                <h2 id="income-pending-title">Pagos por aprobar & notificaciones de pago</h2>
                <p class="admin-page-lead">Verifica el comprobante antes de aprobar. Al aprobar, la membresía del cliente queda activa.</p>
            </header>

            @if ($pendingNotifications->isEmpty() && $pendingBookings->isEmpty())
                <div class="admin-empty-state">
                    <span class="empty-icon">✓</span>
                    <h3>Sin pagos pendientes</h3>
                    <p>Todas las notificaciones de pago han sido verificadas.</p>
                </div>
            @endif

            @if ($pendingNotifications->isNotEmpty())
                <div class="pending-notifications-grid">
                    @foreach ($pendingNotifications as $notification)
                        <article class="pending-notification-card">
                            <header class="pending-notification-head">
                                <div>
                                    <p class="eyebrow">Notificación de pago</p>
                                    <h3>{{ $notification->membership_name }}</h3>
                                    <small class="table-sub">Ref. <code>{{ $notification->reference }}</code> · Enviada {{ $notification->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <span class="status-badge status-badge--procesando">Pendiente de verificación</span>
                            </header>

                            <div class="pending-notification-body">
                                <ul class="pending-notification-data">
                                    <li>
                                        <span>Cliente</span>
                                        <strong>{{ $notification->customer_name }}</strong>
                                    </li>
                                    <li>
                                        <span>Correo</span>
                                        <strong>{{ $notification->customer_email }}</strong>
                                    </li>
                                    <li>
                                        <span>Teléfono</span>
                                        <strong>{{ $notification->customer_phone }}</strong>
                                    </li>
                                    <li>
                                        <span>Plan / Monto</span>
                                        <strong>
                                            ${{ number_format($notification->amount_usd, 2) }} ·
                                            Bs. {{ number_format($notification->amount_ves, 2, ',', '.') }}
                                        </strong>
                                    </li>
                                    <li>
                                        <span>Método de pago</span>
                                        <strong>{{ $notification->payment_method_name }}</strong>
                                    </li>
                                    <li>
                                        <span>N° de referencia bancaria</span>
                                        <strong>{{ $notification->bank_reference }}</strong>
                                    </li>
                                    <li>
                                        <span>Fecha del pago</span>
                                        <strong>{{ $notification->payment_date->format('d/m/Y') }}</strong>
                                    </li>
                                    <li>
                                        <span>Titular de la cuenta origen</span>
                                        <strong>{{ $notification->account_holder_name }}</strong>
                                    </li>
                                    <li>
                                        <span>Tipo de cuenta</span>
                                        <strong>{{ $notification->relationship_label }}</strong>
                                    </li>
                                    <li>
                                        <span>Tasa BCV aplicada</span>
                                        <strong>Bs. {{ number_format($notification->bcv_rate, 2, ',', '.') }} / USD</strong>
                                    </li>
                                </ul>

                                @if ($notification->proof_url)
                                    <button type="button"
                                            class="pending-proof-trigger"
                                            data-proof-url="{{ $notification->proof_url }}"
                                            data-proof-pdf="{{ $notification->proof_is_pdf ? '1' : '0' }}"
                                            data-proof-title="{{ $notification->reference }}">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                        Ver comprobante de pago
                                    </button>
                                @endif
                            </div>

                            <footer class="pending-notification-actions">
                                <form method="POST" action="{{ route('admin.incomes.approve', $notification) }}" class="inline-form">
                                    @csrf
                                    <button type="submit" class="button button--small button--rust">
                                        <span aria-hidden="true">✓</span> Aprobar pago
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.incomes.reject', $notification) }}" class="inline-form" data-confirm-reject>
                                    @csrf
                                    <button type="submit" class="button button--small button--outline">
                                        <span aria-hidden="true">✕</span> Rechazar pago
                                    </button>
                                </form>
                            </footer>
                        </article>
                    @endforeach
                </div>
            @endif

            @if ($pendingBookings->isNotEmpty())
                <h3 class="pending-section-title">Reservas de eventos pendientes</h3>
                <div class="admin-table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Referencia</th>
                                <th>Evento</th>
                                <th>Cliente</th>
                                <th>Monto</th>
                                <th>Ref. bancaria</th>
                                <th>Fecha de pago</th>
                                <th>Comprobante</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pendingBookings as $booking)
                                <tr>
                                    <td><code>{{ $booking->reference }}</code></td>
                                    <td>{{ $booking->event?->title ?? 'Evento' }}</td>
                                    <td>{{ $booking->attendees->first()?->full_name ?? '—' }}</td>
                                    <td><strong>${{ number_format((float) $booking->total_amount, 2) }}</strong></td>
                                    <td>{{ $booking->payment_reference ?? '—' }}</td>
                                    <td>{{ $booking->payment_date?->format('d/m/Y') ?? '—' }}</td>
                                    <td>
                                        @if ($booking->proof_path)
                                            <button type="button"
                                                    class="pending-proof-trigger text-link"
                                                    data-proof-url="{{ asset('storage/' . $booking->proof_path) }}"
                                                    data-proof-pdf="{{ str_ends_with(strtolower($booking->proof_path), '.pdf') ? '1' : '0' }}"
                                                    data-proof-title="{{ $booking->reference }}">
                                                Ver comprobante
                                            </button>
                                        @else
                                            <span class="table-sub">Sin comprobante</span>
                                        @endif
                                    </td>
                                    <td class="table-actions">
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="button button--small button--outline">Gestionar</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    @endif
</div>

<div class="proof-modal" id="proof-modal" hidden>
    <div class="proof-modal-backdrop" data-proof-close></div>
    <div class="proof-modal-dialog" role="dialog" aria-modal="true" aria-label="Comprobante de pago">
        <header class="proof-modal-head">
            <strong id="proof-modal-title">Comprobante de pago</strong>
            <button type="button" class="proof-modal-close" data-proof-close aria-label="Cerrar">&times;</button>
        </header>
        <div class="proof-modal-body" id="proof-modal-body"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (() => {
        const modal = document.getElementById('proof-modal');
        const modalBody = document.getElementById('proof-modal-body');
        const modalTitle = document.getElementById('proof-modal-title');

        if (!modal) {
            return;
        }

        const closeModal = () => {
            modal.hidden = true;
            modalBody.innerHTML = '';
        };

        document.querySelectorAll('[data-proof-close]').forEach((element) => {
            element.addEventListener('click', closeModal);
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !modal.hidden) {
                closeModal();
            }
        });

        document.querySelectorAll('[data-proof-url]').forEach((trigger) => {
            trigger.addEventListener('click', () => {
                const url = trigger.dataset.proofUrl;
                const isPdf = trigger.dataset.proofPdf === '1';
                modalTitle.textContent = `Comprobante · ${trigger.dataset.proofTitle ?? ''}`;
                modalBody.innerHTML = isPdf
                    ? `<iframe src="${url}" title="Comprobante de pago"></iframe>`
                    : `<img src="${url}" alt="Comprobante de pago">`;
                modal.hidden = false;
            });
        });

        document.querySelectorAll('[data-confirm-reject]').forEach((form) => {
            form.addEventListener('submit', (event) => {
                if (!window.confirm('¿Rechazar este pago? El cliente será notificado por correo.')) {
                    event.preventDefault();
                }
            });
        });
    })();
</script>
@endpush
