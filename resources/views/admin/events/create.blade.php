@extends('layouts.admin')

@section('title', 'Crear Evento')

@section('content')
<div class="admin-form animate__animated animate__fadeInUp">
    <header class="admin-form-header">
        <h2>Nuevo evento</h2>
        <a href="{{ route('admin.events.index') }}" class="button button--small button--outline">Cancelar</a>
    </header>

    <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" class="admin-form-body">
        @csrf

        @if ($errors->any())
            <div class="form-errors animate__animated animate__shakeX" style="margin-bottom: 24px;">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="admin-form-row">
            <div class="admin-form-group">
                <label for="title">Título *</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required maxlength="255">
            </div>

            <div class="admin-form-group">
                <label for="category">Categoría *</label>
                <input type="text" id="category" name="category" value="{{ old('category') }}" required maxlength="80" placeholder="ej. CENA · NETWORKING">
            </div>
        </div>

        <div class="admin-form-group">
            <label for="description">Descripción *</label>
            <textarea id="description" name="description" required rows="4">{{ old('description') }}</textarea>
        </div>

        <div class="admin-form-row">
            <div class="admin-form-group">
                <label for="place">Lugar *</label>
                <input type="text" id="place" name="place" value="{{ old('place') }}" required maxlength="120" placeholder="ej. Casa Hi, Polanco">
            </div>

            <div class="admin-form-group">
                <label for="starts_at">Fecha y hora *</label>
                <input type="datetime-local" id="starts_at" name="starts_at" value="{{ old('starts_at', now()->addDays(7)->format('Y-m-d\TH:i')) }}" required>
            </div>
        </div>

        <div class="admin-form-row">
            <div class="admin-form-group">
                <label for="price">Precio por entrada *</label>
                <input type="number" id="price" name="price" value="{{ old('price', 0) }}" required min="0" step="0.01" placeholder="0.00">
            </div>

            <div class="admin-form-group">
                <label for="capacity">Aforo máximo *</label>
                <input type="number" id="capacity" name="capacity" value="{{ old('capacity', 50) }}" required min="1" placeholder="50">
            </div>
        </div>

        <div class="admin-form-group">
            <label for="image">Imagen del evento</label>
            <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp" data-preview="image-preview">
            <p class="form-hint">Formatos: JPG, PNG, WebP. Máx. 2MB. Ratio recomendado 16:9</p>
            @if (old('image'))
                <img id="image-preview" class="image-preview" src="#" alt="Vista previa" hidden>
            @endif
        </div>

        <fieldset class="admin-form-group">
            <legend>Métodos de pago de este evento</legend>
            @if ($paymentMethods->isEmpty())
                <div class="admin-empty-state" style="padding: 32px 24px;">
                    <span class="empty-icon">🏦</span>
                    <h3>No hay métodos de pago activos</h3>
                    <p>Crea y activa métodos de pago desde <a href="{{ route('admin.payment-methods.index') }}">Métodos de Pago</a> para que los clientes puedan reportar sus transferencias.</p>
                </div>
            @else
                <div class="payment-methods" role="group" aria-label="Métodos de pago disponibles para el evento">
                    @foreach ($paymentMethods as $method)
                        <label class="payment-method">
                            <input type="checkbox" name="payment_methods[]" value="{{ $method->id }}" {{ in_array($method->id, (array) old('payment_methods', [])) ? 'checked' : '' }}>
                            <span class="payment-method-icon" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                            </span>
                            <span class="payment-method-text">
                                <strong>{{ $method->name }}</strong>
                                <small>{{ $method->type_label }}{{ $method->bank ? ' · '.$method->bank : '' }}</small>
                            </span>
                        </label>
                    @endforeach
                </div>
                <p class="form-hint" style="margin-top: 12px;">Si no seleccionas ninguno, el checkout usará todos los métodos de pago activos de la configuración global.</p>
                @error('payment_methods.*')<span class="field-error">{{ $message }}</span>@enderror
            @endif
        </fieldset>

        <div class="admin-form-row">
            <div class="admin-form-group" style="display: flex; align-items: end;">
                <label class="checkbox-label" style="margin: 0;">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                    <span>Destacado en página principal</span>
                </label>
            </div>

            <div class="admin-form-group" style="display: flex; align-items: end;">
                <label class="checkbox-label" style="margin: 0;">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}>
                    <span>Publicado</span>
                </label>
            </div>
        </div>

        <div class="admin-form-actions">
            <a href="{{ route('admin.events.index') }}" class="button button--outline">Cancelar</a>
            <button type="submit" class="button button--rust">Crear evento</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const imageInput = document.querySelector('input[name="image"]');
    const preview = document.getElementById('image-preview');

    if (imageInput && preview) {
        imageInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (ev) => {
                    preview.src = ev.target.result;
                    preview.hidden = false;
                };
                reader.readAsDataURL(file);
            } else {
                preview.hidden = true;
                preview.src = '#';
            }
        });
    }
});
</script>
@endpush