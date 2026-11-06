@extends('layouts.admin')

@section('title', 'Panel de Control')

@section('content')
<div class="admin-dashboard animate__animated animate__fadeInUp animate__faster">
    <header class="admin-dashboard-header">
        <div>
            <p class="eyebrow">Panel Directivo</p>
            <h1>Hola, {{ explode(' ', auth('admin')->user()->name)[0] }}. <em>Así va el club.</em></h1>
        </div>
        <a href="{{ route('admin.events.create') }}" class="button button--rust">
            <span aria-hidden="true">+</span> Nuevo evento
        </a>
    </header>

    <div class="kpi-grid kpi-grid--three">
        <article class="kpi-card">
            <span class="kpi-icon" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </span>
            <span class="kpi-value">{{ $stats['active_memberships'] }}</span>
            <span class="kpi-label">Membresías activas</span>
            <span class="kpi-hint">Suscripciones confirmadas</span>
        </article>

        <article class="kpi-card">
            <span class="kpi-icon" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </span>
            <span class="kpi-value">{{ $stats['upcoming_events'] }}</span>
            <span class="kpi-label">Próximos eventos</span>
            <span class="kpi-hint">Publicados en la agenda</span>
        </article>

        <article class="kpi-card kpi-card--warning animate__animated animate__fadeInUp animate__faster">
            <span class="kpi-icon" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </span>
            <span class="kpi-value">{{ $stats['pending_payments'] }}</span>
            <span class="kpi-label">Pagos pendientes</span>
            <span class="kpi-hint">Requieren tu validación</span>
        </article>
    </div>

    <section class="directivo-module animate__animated animate__fadeInUp animate__faster" aria-labelledby="module-events-title">
        <header class="directivo-module-head">
            <div>
                <p class="module-index">Módulo 01</p>
                <h2 id="module-events-title">Gestión de Eventos &amp; Workshops</h2>
            </div>
            <a href="{{ route('admin.events.index') }}" class="text-link">Gestionar todos <span aria-hidden="true">→</span></a>
        </header>

        @if ($events->isEmpty())
            <div class="admin-empty-state">
                <span class="empty-icon">📅</span>
                <h3>Sin eventos registrados</h3>
                <p>Crea tu primer evento o workshop para empezar a vender entradas.</p>
                <a href="{{ route('admin.events.create') }}" class="button button--rust button--small">Crear evento</a>
            </div>
        @else
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Evento</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Capacidad reservada</th>
                            <th>Cupos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                            <tr>
                                <td>
                                    <strong>{{ Str::limit($event->title, 42) }}</strong>
                                    <small class="table-sub">{{ $event->place }}</small>
                                </td>
                                <td>
                                    <span class="status-badge status-badge--{{ $event->is_published ? 'publicado' : 'borrador' }}">
                                        {{ $event->is_published ? 'Publicado' : 'Borrador' }}
                                    </span>
                                </td>
                                <td>{{ $event->starts_at->format('d M Y · H:i') }}</td>
                                <td>
                                    <span class="capacity-meter">{{ $event->sold_tickets_count }}/{{ $event->capacity }}</span>
                                    <span class="capacity-bar" aria-hidden="true">
                                        <span class="capacity-bar-fill" style="width: {{ min(100, (int) round($event->capacity > 0 ? $event->sold_tickets_count / $event->capacity * 100 : 0)) }}%"></span>
                                    </span>
                                </td>
                                <td>{{ $event->available_tickets }}</td>
                                <td class="table-actions">
                                    <a href="{{ route('admin.events.edit', $event) }}" class="button button--small button--outline">Editar</a>
                                    <a href="{{ route('admin.events.show', $event) }}" class="button button--small button--outline">Ver</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <section class="directivo-module animate__animated animate__fadeInUp animate__faster" aria-labelledby="module-memberships-title">
        <header class="directivo-module-head">
            <div>
                <p class="module-index">Módulo 02</p>
                <h2 id="module-memberships-title">Precios &amp; Detalles de Membresías</h2>
            </div>
            <span class="module-note">Los cambios se aplican en tiempo real.</span>
        </header>

        @if ($memberships->isEmpty())
            <div class="admin-empty-state">
                <span class="empty-icon">✳</span>
                <h3>Sin membresías registradas</h3>
                <p>Registra planes en el sistema para controlar tarifas y cupos desde aquí.</p>
            </div>
        @else
            <div class="membership-admin-grid">
                @foreach ($memberships as $membership)
                    <article class="membership-admin-card" data-membership-card="{{ $membership->id }}" data-update-url="{{ route('admin.memberships.update', $membership) }}">
                        <header class="membership-admin-head">
                            <div>
                                <p class="eyebrow">{{ $membership->featured ? 'Plan destacado' : 'Plan' }}</p>
                                <h3>{{ $membership->name }}</h3>
                            </div>
                            @if ($membership->featured)
                                <span class="status-badge status-badge--pagado">Destacado</span>
                            @endif
                        </header>

                        <div class="membership-admin-controls">
                            <div class="membership-admin-field">
                                <label for="membership-price-{{ $membership->id }}">Tarifa mensual (USD)</label>
                                <input type="number" id="membership-price-{{ $membership->id }}" data-membership-price min="0" step="1" value="{{ $membership->price }}">
                            </div>

                            <div class="membership-admin-field">
                                <label for="membership-capacity-{{ $membership->id }}">Cupos disponibles</label>
                                <input type="number" id="membership-capacity-{{ $membership->id }}" data-membership-capacity min="0" step="1" value="{{ $membership->capacity }}">
                            </div>

                            <label class="checkbox-label membership-admin-featured">
                                <input type="checkbox" data-membership-featured {{ $membership->featured ? 'checked' : '' }}>
                                <span>Plan destacado</span>
                            </label>
                        </div>

                        <footer class="membership-admin-foot">
                            <button type="button" class="button button--small button--rust" data-membership-save>
                                Guardar cambios
                            </button>
                            <span class="membership-admin-status" data-membership-status role="status"></span>
                        </footer>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <section class="directivo-module animate__animated animate__fadeInUp animate__faster" aria-labelledby="module-payments-title">
        <header class="directivo-module-head">
            <div>
                <p class="module-index">Módulo 03</p>
                <h2 id="module-payments-title">Historial &amp; Gestión de Pagos Recibidos</h2>
            </div>
            <a href="{{ route('admin.reports.payments') }}" class="button button--small button--outline">
                <span aria-hidden="true">↓</span> Descargar reportes
            </a>
        </header>

        @if ($payments->isEmpty())
            <div class="admin-empty-state">
                <span class="empty-icon">📋</span>
                <h3>Sin pagos registrados</h3>
                <p>Las ventas de eventos y membresías aparecerán aquí automáticamente.</p>
            </div>
        @else
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Referencia</th>
                            <th>Concepto</th>
                            <th>Cliente</th>
                            <th>Método</th>
                            <th>Monto</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            @php
                                $statusClass = ['aprobado' => 'pagado', 'pendiente' => 'procesando'][$payment['status']] ?? $payment['status'];
                            @endphp
                            <tr>
                                <td><code>{{ $payment['reference'] }}</code></td>
                                <td>
                                    {{ $payment['concept'] }}
                                    <small class="table-sub">{{ $payment['type'] === 'membresia' ? 'Membresía' : 'Evento' }}</small>
                                </td>
                                <td>{{ $payment['client'] }}</td>
                                <td>{{ $payment['method'] }}</td>
                                <td>${{ number_format($payment['amount'], 2) }}</td>
                                <td>
                                    <span class="status-badge status-badge--{{ $statusClass }}">{{ $payment['status_label'] }}</span>
                                </td>
                                <td>{{ $payment['date']->format('d/m/Y H:i') }}</td>
                                <td class="table-actions">
                                    @if ($payment['approve_route'])
                                        <form method="POST" action="{{ $payment['approve_route'] }}" class="inline-form">
                                            @csrf
                                            <button type="submit" class="button button--small button--rust">Marcar pagado</button>
                                        </form>
                                        <form method="POST" action="{{ $payment['reject_route'] }}" class="inline-form">
                                            @csrf
                                            <button type="submit" class="button button--small button--outline">Rechazar</button>
                                        </form>
                                    @elseif ($payment['view_route'])
                                        <a href="{{ $payment['view_route'] }}" class="button button--small button--outline">Ver detalle</a>
                                    @else
                                        <span class="table-sub">Gestionado</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</div>
@endsection

@push('scripts')
<script>
    (() => {
        const csrfToken = document.querySelector('input[name="_token"]')?.value
            ?? document.querySelector('meta[name="csrf-token"]')?.content;

        document.querySelectorAll('[data-membership-card]').forEach((card) => {
            const priceInput = card.querySelector('[data-membership-price]');
            const capacityInput = card.querySelector('[data-membership-capacity]');
            const featuredInput = card.querySelector('[data-membership-featured]');
            const saveButton = card.querySelector('[data-membership-save]');
            const statusLabel = card.querySelector('[data-membership-status]');

            saveButton?.addEventListener('click', async () => {
                saveButton.disabled = true;
                statusLabel.textContent = 'Guardando…';
                statusLabel.dataset.state = 'saving';

                try {
                    const response = await fetch(card.dataset.updateUrl, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken ?? '',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({
                            price: priceInput.value,
                            capacity: capacityInput.value,
                            featured: featuredInput.checked,
                        }),
                    });

                    if (!response.ok) {
                        throw new Error('Error de red');
                    }

                    const data = await response.json();
                    statusLabel.textContent = data.message ?? 'Guardado.';
                    statusLabel.dataset.state = 'saved';
                } catch {
                    statusLabel.textContent = 'No pudimos guardar. Intenta de nuevo.';
                    statusLabel.dataset.state = 'error';
                } finally {
                    saveButton.disabled = false;
                }
            });
        });
    })();
</script>
@endpush
