@extends('layouts.admin')

@section('title', 'Panel de Control')

@section('content')
<div class="admin-dashboard animate__animated animate__fadeInUp">
    <header class="admin-dashboard-header">
        <div>
            <p class="eyebrow">Panel de administración</p>
            <h1>Resumen general</h1>
        </div>
        <a href="{{ route('admin.events.create') }}" class="button button--rust">
            <span aria-hidden="true">+</span> Nuevo evento
        </a>
    </header>

    <div class="admin-stats-grid">
        <article class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-content">
                <span class="stat-value">{{ $stats['total_events'] }}</span>
                <span class="stat-label">Eventos totales</span>
            </div>
        </article>

        <article class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-content">
                <span class="stat-value">{{ $stats['published_events'] }}</span>
                <span class="stat-label">Publicados</span>
            </div>
        </article>

        <article class="stat-card">
            <div class="stat-icon">⏳</div>
            <div class="stat-content">
                <span class="stat-value">{{ $stats['upcoming_events'] }}</span>
                <span class="stat-label">Próximos</span>
            </div>
        </article>

        <article class="stat-card stat-card--warning">
            <div class="stat-icon">⏱</div>
            <div class="stat-content">
                <span class="stat-value">{{ $stats['pending_bookings'] }}</span>
                <span class="stat-label">Pagos pendientes</span>
            </div>
        </article>

        <article class="stat-card stat-card--success">
            <div class="stat-icon">✓</div>
            <div class="stat-content">
                <span class="stat-value">{{ $stats['approved_bookings'] }}</span>
                <span class="stat-label">Aprobados</span>
            </div>
        </article>

        <article class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-content">
                <span class="stat-value">{{ $stats['total_attendees'] }}</span>
                <span class="stat-label">Asistentes confirmados</span>
            </div>
        </article>
    </div>

    <div class="admin-dashboard-sections">
        <section class="admin-section">
            <header class="admin-section-header">
                <h2>Reservas recientes</h2>
                <a href="{{ route('admin.bookings.index') }}" class="text-link">Ver todas <span aria-hidden="true">→</span></a>
            </header>

            @if ($recentBookings->isEmpty())
                <div class="admin-empty-state">
                    <span class="empty-icon">📋</span>
                    <h3>Sin reservas recientes</h3>
                    <p>Las nuevas reservas aparecerán aquí automáticamente.</p>
                </div>
            @else
                <div class="admin-table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Referencia</th>
                                <th>Evento</th>
                                <th>Asistentes</th>
                                <th>Monto</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentBookings as $booking)
                                <tr>
                                    <td><code>{{ $booking->reference }}</code></td>
                                    <td>{{ $booking->event->title }}</td>
                                    <td>{{ $booking->tickets_count }}</td>
                                    <td>${{ number_format($booking->total_amount, 2) }}</td>
                                    <td>
                                        <span class="status-badge status-badge--{{ $booking->status }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <section class="admin-section">
            <header class="admin-section-header">
                <h2>Próximos eventos</h2>
                <a href="{{ route('admin.events.index') }}" class="text-link">Ver todas <span aria-hidden="true">→</span></a>
            </header>

            @if ($upcomingEvents->isEmpty())
                <div class="admin-empty-state">
                    <span class="empty-icon">📅</span>
                    <h3>Sin eventos programados</h3>
                    <p>Crea tu primer evento para empezar.</p>
                    <a href="{{ route('admin.events.create') }}" class="button button--rust button--small">Crear evento</a>
                </div>
            @else
                <div class="admin-event-list">
                    @foreach ($upcomingEvents as $event)
                        <article class="admin-event-item">
                            <div class="admin-event-date">
                                <strong>{{ $event->starts_at->format('d') }}</strong>
                                <span>{{ strtoupper($event->starts_at->locale('es')->translatedFormat('M')) }}</span>
                            </div>
                            <div class="admin-event-info">
                                <h3>{{ $event->title }}</h3>
                                <p class="admin-event-meta">{{ $event->starts_at->format('H:i') }} · {{ $event->place }}</p>
                                <p class="admin-event-stats">
                                    <span>{{ $event->sold_tickets_count }}/{{ $event->capacity }} entradas</span>
                                    <span class="admin-event-status {{ $event->is_sold_out ? 'sold-out' : '' }}">
                                        {{ $event->is_sold_out ? 'Agotado' : 'Disponible' }}
                                    </span>
                                </p>
                            </div>
                            <a href="{{ route('admin.events.show', $event) }}" class="button button--small button--outline">Gestionar</a>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
</div>
@endsection