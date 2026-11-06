@extends('layouts.app')

@section('title', 'Reportar Pago de Membresía')

@section('content')
@php
    use App\Models\IncomeNotification;

    $bcvRate = $bcvRate ?? \App\Services\BcvRateService::getRate();
    $priceUsd = (float) $membership->price;
    $totalUsd = $priceUsd;
    $totalVes = $totalUsd * $bcvRate;
    $capacityLabel = is_numeric($membership->capacity ?? null)
        ? $membership->capacity . ' cupos disponibles'
        : ($membership->capacity ?? 'Cupos flexibles');
@endphp
<main id="main-content" class="checkout-page">
    <div class="section-wrap">
        <nav class="checkout-breadcrumb" aria-label="Volver">
            <a href="{{ route('memberships.index') }}" class="text-link"><span aria-hidden="true">←</span> Volver a Membresías</a>
        </nav>

        <header class="checkout-header">
            <p class="eyebrow">Pago de membresía</p>
            <h1>Reporta tu <em>pago</em></h1>
            <p class="checkout-lead">Realiza tu transferencia y envíanos el comprobante para activar <strong>{{ $membership->name }}</strong>.</p>
        </header>

        @if ($paymentMethods->isEmpty())
            <div class="admin-empty-state checkout-empty-state">
                <span class="empty-icon">🏦</span>
                <h3>No hay métodos de pago disponibles</h3>
                <p>Comunícate con nuestro equipo en <a href="mailto:hola@hibranding.com">hola@hibranding.com</a> para recibir las cuentas de pago.</p>
            </div>
        @else
            <div class="checkout-grid">
                <form method="POST"
                      action="{{ route('memberships.checkout.notify', $membership->slug) }}"
                      enctype="multipart/form-data"
                      class="checkout-form-card">
                    @csrf

                    @if ($errors->any())
                        <div class="form-errors" role="alert">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <section class="checkout-block">
                        <p class="checkout-block-index">01 · Método de pago</p>
                        <h2>Selecciona el canal por el que pagaste</h2>

                        <div class="payment-methods" role="radiogroup" aria-label="Métodos de pago disponibles">
                            @foreach ($paymentMethods as $method)
                                <label class="payment-method">
                                    <input type="radio"
                                           name="payment_method_id"
                                           value="{{ $method->id }}"
                                           data-method-panel="{{ $method->id }}"
                                           {{ (int) old('payment_method_id', $paymentMethods->first()->id) === (int) $method->id ? 'checked' : '' }}
                                           required>
                                    <span class="payment-method-icon" aria-hidden="true">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                                    </span>
                                    <span class="payment-method-text">
                                        <strong>{{ $method->name }}</strong>
                                        <small>{{ $method->type_label }}</small>
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        @foreach ($paymentMethods as $method)
                            <section class="payment-instructions"
                                     data-method-panel="{{ $method->id }}"
                                     {{ (int) old('payment_method_id', $paymentMethods->first()->id) === (int) $method->id ? '' : 'hidden' }}>
                                <h3 class="payment-instructions-title">Datos para transferir a {{ $method->name }}</h3>
                                <dl class="payment-instructions-list">
                                    @if ($method->bank)
                                        <div>
                                            <dt>Banco</dt>
                                            <dd>{{ $method->bank }}</dd>
                                        </div>
                                    @endif
                                    @if ($method->tax_id)
                                        <div>
                                            <dt>RIF / DNI</dt>
                                            <dd>{{ $method->tax_id }}</dd>
                                        </div>
                                    @endif
                                    @if ($method->account_number)
                                        <div>
                                            <dt>Número de cuenta / Teléfono</dt>
                                            <dd>{{ $method->account_number }}</dd>
                                        </div>
                                    @endif
                                    @if ($method->account_holder)
                                        <div>
                                            <dt>Titular</dt>
                                            <dd>{{ $method->account_holder }}</dd>
                                        </div>
                                    @endif
                                    @if ($method->notes)
                                        <div>
                                            <dt>Instrucciones</dt>
                                            <dd>{{ $method->notes }}</dd>
                                        </div>
                                    @endif
                                </dl>
                                <p class="method-security-note">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                                    Verifica los datos antes de transferir. Tu pago se valida en menos de 24 horas hábiles.
                                </p>
                            </section>
                        @endforeach
                    </section>

                    <section class="checkout-block">
                        <p class="checkout-block-index">02 · Datos del pago</p>
                        <h2>Confirma los datos de tu transferencia</h2>

                        <div class="form-row">
                            <div class="icon-field">
                                <label for="customer_name">Nombre completo *</label>
                                <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required maxlength="150" autocomplete="name" placeholder="Tu nombre y apellido">
                                @error('customer_name')<span class="field-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="icon-field">
                                <label for="customer_email">Correo electrónico *</label>
                                <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"></rect><line x1="7" y1="8" x2="17" y2="12"></line><line x1="17" y1="8" x2="7" y2="12"></line></svg>
                                <input type="email" id="customer_email" name="customer_email" value="{{ old('customer_email') }}" required maxlength="255" autocomplete="email" placeholder="tucorreo@ejemplo.com">
                                @error('customer_email')<span class="field-error">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="icon-field">
                                <label for="customer_phone">Teléfono / WhatsApp *</label>
                                <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                <input type="text" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required maxlength="30" autocomplete="tel" placeholder="+58 412 1234567">
                                @error('customer_phone')<span class="field-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="icon-field">
                                <label for="bank_reference">N° de referencia bancaria *</label>
                                <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                                <input type="text" id="bank_reference" name="bank_reference" value="{{ old('bank_reference') }}" required maxlength="100" placeholder="Ej. 009876543210">
                                @error('bank_reference')<span class="field-error">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="icon-field">
                                <label for="payment_date">Fecha del pago *</label>
                                <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                <input type="date" id="payment_date" name="payment_date" value="{{ old('payment_date', now()->format('Y-m-d')) }}" required max="{{ now()->format('Y-m-d') }}">
                                @error('payment_date')<span class="field-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="icon-field icon-field--select">
                                <label for="account_holder_name">Titular de la cuenta origen *</label>
                                <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                <input type="text" id="account_holder_name" name="account_holder_name" value="{{ old('account_holder_name') }}" required maxlength="150" placeholder="Nombre como figura en tu banco">
                                @error('account_holder_name')<span class="field-error">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <fieldset class="choice-group">
                            <legend>¿Desde qué cuenta pagaste? *</legend>
                            <label class="choice-option">
                                <input type="radio" name="account_holder_relationship" value="{{ IncomeNotification::RELATIONSHIP_OWN }}" {{ old('account_holder_relationship', IncomeNotification::RELATIONSHIP_OWN) === IncomeNotification::RELATIONSHIP_OWN ? 'checked' : '' }} required>
                                <span>Cuenta propia</span>
                            </label>
                            <label class="choice-option">
                                <input type="radio" name="account_holder_relationship" value="{{ IncomeNotification::RELATIONSHIP_THIRD }}" {{ old('account_holder_relationship') === IncomeNotification::RELATIONSHIP_THIRD ? 'checked' : '' }}>
                                <span>Cuenta de un tercero</span>
                            </label>
                            @error('account_holder_relationship')<span class="field-error">{{ $message }}</span>@enderror
                        </fieldset>
                    </section>

                    <section class="checkout-block">
                        <p class="checkout-block-index">03 · Comprobante</p>
                        <h2>Adjunta tu comprobante de pago</h2>

                        <div class="icon-field proof-upload-field">
                            <input type="file"
                                   id="proof_path"
                                   name="proof_path"
                                   accept="image/jpeg,image/png,application/pdf"
                                   class="hidden"
                                   required
                                   onchange="document.getElementById('file-name-display').innerText = this.files[0]?.name || 'Sin archivos seleccionados'">
                            <label for="proof_path" class="proof-upload-button">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                Seleccionar archivo
                            </label>
                            <span id="file-name-display" class="proof-upload-filename" role="status">Sin archivos seleccionados</span>
                            <label for="proof_path" class="field-label">Captura o comprobante (JPG, PNG o PDF) *</label>
                            @error('proof_path')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        <p class="method-security-note">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                            Formatos aceptados: JPG, PNG o PDF. Tamaño máximo 5 MB.
                        </p>
                    </section>

                    <button type="submit" class="button button--rust button--large button--full checkout-submit">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <span>ENVIAR COMPROBANTE</span>
                        <span aria-hidden="true">→</span>
                    </button>
                    <p class="checkout-submit-note">Al enviar aceptas los términos del club. Revisaremos tu pago y te confirmaremos por correo.</p>
                </form>

                <aside class="checkout-aside" aria-label="Resumen de la orden">
                    <div class="checkout-summary-card">
                        <div class="summary-plan-photo plan-photo plan-photo--{{ $planIndex }}">
                            <span>{{ $capacityLabel }}</span>
                        </div>

                        <div class="summary-plan-body">
                            <p class="eyebrow">Tu plan seleccionado</p>
                            <h2>{{ $membership->name }}</h2>
                            <p class="summary-plan-description">{{ $membership->description }}</p>

                            <ul class="summary-plan-features">
                                @foreach ($membership->features ?? [] as $feature)
                                    <li><span aria-hidden="true">✳</span>{{ $feature }}</li>
                                @endforeach
                                <li><span aria-hidden="true">✳</span>{{ $capacityLabel }}</li>
                            </ul>

                            <dl class="summary-price-breakdown">
                                <div class="summary-price-row">
                                    <dt>Precio de la membresía</dt>
                                    <dd>${{ number_format($totalUsd, 2) }}</dd>
                                </div>
                                <div class="summary-price-row">
                                    <dt>Tasa BCV del día</dt>
                                    <dd>Bs. {{ number_format($bcvRate, 2, ',', '.') }}</dd>
                                </div>
                                <div class="summary-price-row summary-price-row--total">
                                    <dt>Equivalente en bolívares</dt>
                                    <dd>Bs. {{ number_format($totalVes, 2, ',', '.') }}</dd>
                                </div>
                            </dl>

                            <p class="summary-bcv-note">
                                El monto en bolívares se calcula con la tasa BCV del momento del reporte y se redondea a dos decimales.
                            </p>

                            <div class="summary-trust">
                                <div class="summary-trust-item">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                                    <span>Pago verificado por el equipo</span>
                                </div>
                                <div class="summary-trust-item">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"></rect><line x1="7" y1="8" x2="17" y2="12"></line><line x1="17" y1="8" x2="7" y2="12"></line></svg>
                                    <span>Comprobante cifrado y privado</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        @endif
    </div>
</main>
@endsection

@push('scripts')
<script>
    (() => {
        const inputs = document.querySelectorAll('input[name="payment_method_id"]');
        const panels = document.querySelectorAll('[data-method-panel]:not(input)');

        inputs.forEach((input) => {
            input.addEventListener('change', () => {
                panels.forEach((panel) => {
                    panel.hidden = panel.dataset.methodPanel !== input.dataset.methodPanel;
                });
            });
        });
    })();
</script>
@endpush