@extends('layouts.app')

@section('title', 'Comprobante Enviado')

@section('content')
<main id="main-content" class="checkout-confirmation-page">
    <div class="section-wrap">
        <article class="confirmation-card">
            <span class="success-icon" aria-hidden="true">✓</span>

            <p class="eyebrow">Comprobante recibido</p>
            <h1>Recibimos tu pago de <em>{{ $notification->membership_name }}</em></h1>

            <p class="confirmation-reference">Referencia: <strong>{{ $notification->reference }}</strong></p>

            <p class="confirmation-lead">
                Nuestro equipo concierge validará tu pago
                ({{ $notification->payment_method_name }}) y te escribiremos a
                <strong>{{ $notification->customer_email }}</strong> en menos de 24 horas hábiles.
            </p>

            <dl class="confirmation-details">
                <div class="summary-price-row">
                    <dt>Plan</dt>
                    <dd>{{ $notification->membership_name }}</dd>
                </div>
                <div class="summary-price-row">
                    <dt>Método de pago</dt>
                    <dd>{{ $notification->payment_method_name }}</dd>
                </div>
                <div class="summary-price-row">
                    <dt>Referencia bancaria</dt>
                    <dd>{{ $notification->bank_reference }}</dd>
                </div>
                <div class="summary-price-row">
                    <dt>Fecha del pago</dt>
                    <dd>{{ $notification->payment_date->format('d/m/Y') }}</dd>
                </div>
                <div class="summary-price-row">
                    <dt>Titular de la cuenta</dt>
                    <dd>{{ $notification->account_holder_name }} · {{ $notification->relationship_label }}</dd>
                </div>
                <div class="summary-price-row">
                    <dt>Tasa BCV aplicada</dt>
                    <dd>Bs. {{ number_format($notification->bcv_rate, 2, ',', '.') }} / USD</dd>
                </div>
                <div class="summary-price-row">
                    <dt>Monto reportado</dt>
                    <dd>${{ number_format((float) $notification->amount_usd, 2) }}</dd>
                </div>
                <div class="summary-price-row summary-price-row--total">
                    <dt>Equivalente</dt>
                    <dd>Bs. {{ number_format((float) $notification->amount_ves, 2, ',', '.') }}</dd>
                </div>
            </dl>

            <div class="confirmation-actions">
                <a class="button button--rust" href="{{ route('home') }}">Volver al inicio <span aria-hidden="true">↗</span></a>
                <a class="button button--outline" href="{{ route('events.index') }}">Explorar eventos del club <span aria-hidden="true">→</span></a>
            </div>

            <p class="confirmation-note">¿Dudas? Escríbenos a <a href="mailto:hola@hibranding.com">hola@hibranding.com</a> — Soporte Concierge 1:1.</p>
        </article>
    </div>
</main>
@endsection