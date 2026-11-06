@extends('layouts.admin')

@section('title', 'Clientes & Audiencia')

@section('content')
<div class="admin-dashboard animate__animated animate__fadeInUp animate__faster">
    <header class="admin-dashboard-header">
        <div>
            <p class="eyebrow">Administración</p>
            <h1>Clientes & <em>Audiencia</em></h1>
        </div>
        <a href="{{ route('community.index') }}" class="button button--outline">
            Ver directorio público <span aria-hidden="true">↗</span>
        </a>
    </header>

    <section class="directivo-module" aria-labelledby="module-clients-title">
        <header class="directivo-module-head">
            <div>
                <p class="module-index">Directorio</p>
                <h2 id="module-clients-title">Miembros de la comunidad</h2>
            </div>
            <span class="module-note">{{ $clients->count() }} {{ Str::plural('registro', $clients->count()) }}</span>
        </header>

        @if ($clients->isEmpty())
            <div class="admin-empty-state">
                <span class="empty-icon">👥</span>
                <h3>Sin miembros registrados</h3>
                <p>La audiencia del club aparecerá aquí a medida que se registren en la comunidad.</p>
            </div>
        @else
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Miembro</th>
                            <th>Rol</th>
                            <th>Organización</th>
                            <th>Categoría</th>
                            <th>Correo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clients as $client)
                            <tr>
                                <td class="client-cell">
                                    <span class="client-avatar client-avatar--{{ $client->tone ?? 'clay' }}" aria-hidden="true">
                                        @if ($client->photo_url)
                                            <img src="{{ $client->photo_url }}" alt="" loading="lazy">
                                        @else
                                            {{ $client->initials ?? mb_strtoupper(mb_substr($client->name, 0, 2)) }}
                                        @endif
                                    </span>
                                    <strong>{{ $client->name }}</strong>
                                </td>
                                <td>{{ $client->role }}</td>
                                <td>{{ $client->company }}</td>
                                <td><span class="status-badge status-badge--tag">{{ $client->category }}</span></td>
                                <td><a href="mailto:{{ $client->email }}" class="table-link">{{ $client->email }}</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</div>
@endsection
