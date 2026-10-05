@extends('layouts.admin')

@section('title', 'Gestión de Eventos')

@section('content')
<div class="admin-events-page animate__animated animate__fadeInUp">
    <header class="admin-events-header">
        <div>
            <p class="eyebrow">Administración</p>
            <h1>Eventos</h1>
        </div>
        <a href="{{ route('admin.events.create') }}" class="button button--rust">
            <span aria-hidden="true">+</span> Nuevo evento
        </a>
    </header>

    <div class="admin-events-table">
        <table>
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Evento</th>
                    <th>Fecha</th>
                    <th>Lugar</th>
                    <th>Precio</th>
                    <th>Aforo</th>
                    <th>Vendidas</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($events as $event)
                    <tr>
                        <td>
                            @if ($event->image)
                                <img src="{{ asset('storage/' . $event->image) }}" alt="" class="admin-event-thumbnail">
                            @else
                                <span class="text-muted">Sin imagen</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $event->title }}</strong>
                            <br><small class="text-muted">{{ $event->category }}</small>
                        </td>
                        <td>{{ $event->starts_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $event->place }}</td>
                        <td>${{ number_format($event->price, 2) }}</td>
                        <td>{{ $event->capacity }}</td>
                        <td>{{ $event->sold_tickets_count }}</td>
                        <td>
                            <span class="status-badge {{ $event->is_published ? 'status-badge--aprobado' : 'status-badge--pendiente' }}">
                                {{ $event->is_published ? 'Publicado' : 'Borrador' }}
                            </span>
                            @if ($event->is_featured)
                                <span class="status-badge status-badge--warning" style="margin-top: 4px; display: inline-block;">Destacado</span>
                            @endif
                        </td>
                        <td>
                            <div class="admin-actions">
                                <a href="{{ route('admin.events.show', $event) }}" class="button button--small button--outline">Ver</a>
                                <a href="{{ route('admin.events.edit', $event) }}" class="button button--small button--cream">Editar</a>
                                <form method="POST" action="{{ route('admin.events.destroy', $event) }}" class="admin-delete-form" onsubmit="return confirm('¿Eliminar este evento?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button button--small button--outline" style="color: #a43b2b; border-color: #e8c8c0;">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="admin-empty-state">
                            <span class="empty-icon">📅</span>
                            <h3>No hay eventos</h3>
                            <p>Crea tu primer evento para empezar.</p>
                            <a href="{{ route('admin.events.create') }}" class="button button--rust button--small" style="margin-top: 16px;">Crear evento</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $events->links() }}
</div>
@endsection