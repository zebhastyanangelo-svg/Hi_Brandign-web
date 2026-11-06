@extends('layouts.admin')

@section('title', 'Métodos de Pago')

@section('content')
<div class="admin-page admin-page--payment-methods">
    <header class="admin-page-header">
        <div>
            <p class="eyebrow">Canales de cobro</p>
            <h1>Métodos de <em>Pago</em></h1>
            <p class="admin-page-lead">Gestiona los canales de pago que verán tus clientes en el checkout de membresías y eventos.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="button button--small button--outline">← Panel de control</a>
    </header>

    <div class="payment-methods-admin-layout">
        <section class="income-panel" aria-labelledby="pm-create-title">
            <header class="income-panel-head">
                <h2 id="pm-create-title">Nuevo método de pago</h2>
            </header>

            <form method="POST" action="{{ route('admin.payment-methods.store') }}" class="payment-method-form">
                @csrf

                <div class="income-filter-field">
                    <label for="pm-name">Nombre / Tipo *</label>
                    <input type="text" id="pm-name" name="name" value="{{ old('name') }}" required maxlength="120" placeholder="Ej. Pago Móvil Banesco">
                    @error('name')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="income-filter-field">
                    <label for="pm-type">Tipo de canal *</label>
                    <select id="pm-type" name="type" required>
                        @foreach ($types as $value => $label)
                            <option value="{{ $value }}" {{ old('type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="income-filter-field">
                    <label for="pm-bank">Banco / Plataforma</label>
                    <input type="text" id="pm-bank" name="bank" value="{{ old('bank') }}" maxlength="120" placeholder="Ej. Banesco, Mercantil, Binance">
                    @error('bank')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="income-filter-field">
                    <label for="pm-tax-id">RIF / DNI del titular</label>
                    <input type="text" id="pm-tax-id" name="tax_id" value="{{ old('tax_id') }}" maxlength="50" placeholder="Ej. J-12345678-9">
                    @error('tax_id')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="income-filter-field">
                    <label for="pm-account">Número de cuenta / Teléfono</label>
                    <input type="text" id="pm-account" name="account_number" value="{{ old('account_number') }}" maxlength="120" placeholder="Ej. 0134-0000-00-00000000">
                    @error('account_number')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="income-filter-field">
                    <label for="pm-holder">Titular de la cuenta</label>
                    <input type="text" id="pm-holder" name="account_holder" value="{{ old('account_holder') }}" maxlength="150" placeholder="Ej. Hi Branding C.A.">
                    @error('account_holder')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="income-filter-field">
                    <label for="pm-notes">Notas u observaciones</label>
                    <textarea id="pm-notes" name="notes" rows="3" maxlength="1000" placeholder="Instrucciones para el cliente al momento de pagar.">{{ old('notes') }}</textarea>
                    @error('notes')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="income-filter-field">
                    <label for="pm-sort">Orden de despliegue</label>
                    <input type="number" id="pm-sort" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" step="1">
                    @error('sort_order')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <label class="checkbox-label">
                    <input type="checkbox" name="is_active" value="1" checked>
                    <span>Método activo (visible en el checkout)</span>
                </label>

                <button type="submit" class="button button--rust">
                    <span aria-hidden="true">+</span> Crear método de pago
                </button>
            </form>
        </section>

        <section class="income-panel" aria-labelledby="pm-list-title">
            <header class="income-panel-head">
                <h2 id="pm-list-title">Métodos configurados</h2>
                <p class="admin-page-lead">Actívalos o desactívalos para controlar su visibilidad en el checkout.</p>
            </header>

            @if ($paymentMethods->isEmpty())
                <div class="admin-empty-state">
                    <span class="empty-icon">🏦</span>
                    <h3>Sin métodos de pago</h3>
                    <p>Crea tu primer método para que los clientes puedan reportar sus pagos.</p>
                </div>
            @else
                <div class="admin-table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Método</th>
                                <th>Banco / Plataforma</th>
                                <th>Cuenta / Teléfono</th>
                                <th>Titular</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paymentMethods as $method)
                                <tr>
                                    <td>
                                        <strong>{{ $method->name }}</strong>
                                        <small class="table-sub">{{ $method->type_label }}</small>
                                    </td>
                                    <td>{{ $method->bank ?? '—' }}
                                        @if ($method->tax_id)
                                            <small class="table-sub">{{ $method->tax_id }}</small>
                                        @endif
                                    </td>
                                    <td><code>{{ $method->account_number ?? '—' }}</code></td>
                                    <td>{{ $method->account_holder ?? '—' }}</td>
                                    <td>
                                        <span class="status-badge status-badge--{{ $method->is_active ? 'pagado' : 'borrador' }}">
                                            {{ $method->is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="table-actions">
                                        <a href="{{ route('admin.payment-methods.edit', $method) }}" class="button button--small button--outline">Editar</a>
                                        <form method="POST" action="{{ route('admin.payment-methods.toggle', $method) }}" class="inline-form">
                                            @csrf
                                            <button type="submit" class="button button--small {{ $method->is_active ? 'button--outline' : 'button--rust' }}">
                                                {{ $method->is_active ? 'Desactivar' : 'Activar' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.payment-methods.destroy', $method) }}" class="inline-form" data-confirm-delete>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="button button--small button--danger">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-confirm-delete]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm('¿Eliminar este método de pago? Esta acción no se puede deshacer.')) {
                event.preventDefault();
            }
        });
    });
</script>
@endpush
