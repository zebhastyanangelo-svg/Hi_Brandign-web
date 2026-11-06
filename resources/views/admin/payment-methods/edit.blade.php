@extends('layouts.admin')

@section('title', 'Editar Método de Pago')

@section('content')
<div class="admin-page admin-page--payment-methods">
    <header class="admin-page-header">
        <div>
            <p class="eyebrow">Configuración de cobros</p>
            <h1>Editar <em>{{ $paymentMethod->name }}</em></h1>
            <p class="admin-page-lead">Los cambios se reflejan de inmediato en el checkout público.</p>
        </div>
        <a href="{{ route('admin.payment-methods.index') }}" class="button button--small button--outline">← Volver a métodos de pago</a>
    </header>

    <section class="income-panel" aria-labelledby="pm-edit-title">
        <header class="income-panel-head">
            <h2 id="pm-edit-title">Datos de la cuenta</h2>
        </header>

        <form method="POST" action="{{ route('admin.payment-methods.update', $paymentMethod) }}" class="payment-method-form">
            @csrf
            @method('PUT')

            <div class="income-filter-field">
                <label for="pm-name">Nombre / Tipo *</label>
                <input type="text" id="pm-name" name="name" value="{{ old('name', $paymentMethod->name) }}" required maxlength="120" placeholder="Ej. Pago Móvil Banesco">
                @error('name')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="income-filter-field">
                <label for="pm-type">Tipo de canal *</label>
                <select id="pm-type" name="type" required>
                    @foreach ($types as $value => $label)
                        <option value="{{ $value }}" {{ old('type', $paymentMethod->type) === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('type')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="income-filter-field">
                <label for="pm-bank">Banco / Plataforma</label>
                <input type="text" id="pm-bank" name="bank" value="{{ old('bank', $paymentMethod->bank) }}" maxlength="120" placeholder="Ej. Banesco, Mercantil, Binance">
                @error('bank')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="income-filter-field">
                <label for="pm-tax-id">RIF / DNI del titular</label>
                <input type="text" id="pm-tax-id" name="tax_id" value="{{ old('tax_id', $paymentMethod->tax_id) }}" maxlength="50" placeholder="Ej. J-12345678-9">
                @error('tax_id')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="income-filter-field">
                <label for="pm-account">Número de cuenta / Teléfono</label>
                <input type="text" id="pm-account" name="account_number" value="{{ old('account_number', $paymentMethod->account_number) }}" maxlength="120" placeholder="Ej. 0134-0000-00-00000000">
                @error('account_number')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="income-filter-field">
                <label for="pm-holder">Titular de la cuenta</label>
                <input type="text" id="pm-holder" name="account_holder" value="{{ old('account_holder', $paymentMethod->account_holder) }}" maxlength="150" placeholder="Ej. Hi Branding C.A.">
                @error('account_holder')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="income-filter-field">
                <label for="pm-notes">Notas u observaciones</label>
                <textarea id="pm-notes" name="notes" rows="3" maxlength="1000" placeholder="Instrucciones para el cliente al momento de pagar.">{{ old('notes', $paymentMethod->notes) }}</textarea>
                @error('notes')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="income-filter-field">
                <label for="pm-sort">Orden de despliegue</label>
                <input type="number" id="pm-sort" name="sort_order" value="{{ old('sort_order', $paymentMethod->sort_order) }}" min="0" step="1">
                @error('sort_order')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <label class="checkbox-label">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $paymentMethod->is_active) ? 'checked' : '' }}>
                <span>Método activo (visible en el checkout)</span>
            </label>

            <div class="payment-method-form-actions">
                <button type="submit" class="button button--rust">Guardar cambios</button>
                <a href="{{ route('admin.payment-methods.index') }}" class="button button--outline">Cancelar</a>
            </div>
        </form>
    </section>
</div>
@endsection