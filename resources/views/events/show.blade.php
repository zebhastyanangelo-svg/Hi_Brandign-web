@extends('layouts.app')

@section('title', $event->title)

@section('content')
<main id="main-content" class="event-page">
    <!-- Event Hero -->
    <header class="event-hero">
        @if ($event->image)
            <div class="event-hero-image" style="background-image: url('{{ asset('storage/' . $event->image) }}');"></div>
        @else
            <div class="event-hero-image" style="background: #4d413b;"></div>
        @endif
        <div class="event-hero-shade"></div>
        <div class="event-hero-content">
            <p class="eyebrow">{{ $event->category }}</p>
            <h1>{{ $event->title }}</h1>
            <div class="event-hero-meta">
                <span>{{ $event->starts_at->locale('es')->translatedFormat('l, j F Y') }}</span>
                <span>{{ $event->starts_at->format('H:i') }} hrs</span>
                <span>{{ $event->place }}</span>
            </div>

            @if ($event->starts_at > now())
                <div class="countdown-timer" data-event-date="{{ $event->starts_at->toISOString() }}">
                    <div class="countdown-item">
                        <span class="countdown-value" data-days>00</span>
                        <span class="countdown-label">Días</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-value" data-hours>00</span>
                        <span class="countdown-label">Horas</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-value" data-minutes>00</span>
                        <span class="countdown-label">Min</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-value" data-seconds>00</span>
                        <span class="countdown-label">Seg</span>
                    </div>
                </div>
            @endif
        </div>
    </header>

    <!-- Event Details -->
    <section class="event-details section-wrap">
        <div class="event-main">
            <div class="event-description">
                {!! nl2br(e($event->description)) !!}
            </div>

            @if ($event->starts_at > now() && !$event->is_sold_out)
                <div class="event-purchase-section">
                    <div class="event-price">
                        <span class="price-label">Precio por entrada</span>
                        <span class="price-value">${{ number_format($event->price, 2) }}</span>
                    </div>
                    <div class="event-availability">
                        <span>{{ $event->available_tickets }} de {{ $event->capacity }} entradas disponibles</span>
                    </div>
                    <button type="button" class="button button--rust button--large" data-open-checkout>
                        <span aria-hidden="true">🎫</span> Comprar entrada
                    </button>
                </div>
            @elseif ($event->is_sold_out)
                <div class="event-sold-out">
                    <span class="sold-out-icon">🔒</span>
                    <h3>Agotado</h3>
                    <p>Todas las entradas han sido vendidas.</p>
                </div>
            @else
                <div class="event-ended">
                    <span class="ended-icon">📅</span>
                    <h3>Evento finalizado</h3>
                    <p>Este evento ya ha ocurrido.</p>
                </div>
            @endif
        </div>

        <aside class="event-sidebar">
            <div class="event-info-card">
                <h3>Información del evento</h3>
                <dl class="event-info-list">
                    <div>
                        <dt>Fecha</dt>
                        <dd>{{ $event->starts_at->locale('es')->translatedFormat('l, j F Y') }}</dd>
                    </div>
                    <div>
                        <dt>Hora</dt>
                        <dd>{{ $event->starts_at->format('H:i') }} hrs</dd>
                    </div>
                    <div>
                        <dt>Lugar</dt>
                        <dd>{{ $event->place }}</dd>
                    </div>
                    <div>
                        <dt>Precio</dt>
                        <dd>${{ number_format($event->price, 2) }}</dd>
                    </div>
                    <div>
                        <dt>Aforo</dt>
                        <dd>{{ $event->capacity }} personas</dd>
                    </div>
                </dl>
            </div>

            <div class="event-info-card bank-details-card">
                <h3>Datos para transferencia</h3>
                <dl class="event-info-list">
                    <div>
                        <dt>Banco</dt>
                        <dd>{{ $bankDetails['bank'] }}</dd>
                    </div>
                    <div>
                        <dt>Cuenta</dt>
                        <dd>{{ $bankDetails['account'] }}</dd>
                    </div>
                    <div>
                        <dt>Titular</dt>
                        <dd>{{ $bankDetails['holder'] }}</dd>
                    </div>
                    <div>
                        <dt>Cédula / RIF</dt>
                        <dd>{{ $bankDetails['document'] }}</dd>
                    </div>
                </dl>
                <p class="bank-details-note">Realiza la transferencia y guarda el comprobante para el siguiente paso.</p>
            </div>
        </aside>
    </section>
</main>

<!-- Checkout Modal -->
<dialog id="checkout-modal" class="booking-dialog animate__animated animate__fadeInUp">
    <form id="checkout-form" class="booking-form">
        <button type="button" class="dialog-close" aria-label="Cerrar">×</button>

        <!-- Step 1: Ticket Selection -->
        <div class="checkout-step" data-step="1">
            <p class="eyebrow">Paso 1 de 4</p>
            <h2>Selecciona tus entradas</h2>
            <p class="form-intro">Disponibles: <strong id="available-tickets">{{ $event->available_tickets }}</strong> entradas · Precio: <strong>${{ number_format($event->price, 2) }}</strong> c/u</p>

            <div class="ticket-selector">
                <label for="ticket-quantity">Cantidad</label>
                <div class="quantity-input">
                    <button type="button" class="qty-btn" data-action="decrease" aria-label="Disminuir">−</button>
                    <input type="number" id="ticket-quantity" name="tickets" value="1" min="1" max="{{ $event->available_tickets }}" required readonly>
                    <button type="button" class="qty-btn" data-action="increase" aria-label="Aumentar">+</button>
                </div>
            </div>

            <div class="checkout-summary">
                <div class="summary-row">
                    <span>{{ $event->price }} × <span id="summary-qty">1</span></span>
                    <span id="summary-total">${{ number_format($event->price, 2) }}</span>
                </div>
            </div>

            <div class="checkout-navigation">
                <button type="button" class="button button--outline" disabled>Anterior</button>
                <button type="button" class="button button--rust next-step" data-next="2">Siguiente</button>
            </div>
        </div>

        <!-- Step 2: Attendees -->
        <div class="checkout-step" data-step="2" hidden>
            <p class="eyebrow">Paso 2 de 4</p>
            <h2>Datos de los asistentes</h2>
            <p class="form-intro">Completa la información de cada persona. Estos datos aparecerán en la lista de asistencia.</p>

            <div id="attendees-container"></div>

            <div class="checkout-navigation">
                <button type="button" class="button button--outline prev-step" data-prev="1">Anterior</button>
                <button type="button" class="button button--rust next-step" data-next="3">Siguiente</button>
            </div>
        </div>

        <!-- Step 3: Payment Details -->
        <div class="checkout-step" data-step="3" hidden>
            <p class="eyebrow">Paso 3 de 4</p>
            <h2>Instrucciones de pago</h2>
            <p class="form-intro">Realiza la transferencia con los siguientes datos y conserva el comprobante.</p>

            <div class="bank-details-card" style="margin-bottom: 24px;">
                <dl class="bank-details-grid">
                    <div class="bank-detail-item">
                        <span class="bank-detail-label">Banco</span>
                        <span class="bank-detail-value">{{ $bankDetails['bank'] }}</span>
                    </div>
                    <div class="bank-detail-item">
                        <span class="bank-detail-label">Cuenta</span>
                        <span class="bank-detail-value">{{ $bankDetails['account'] }}</span>
                    </div>
                    <div class="bank-detail-item">
                        <span class="bank-detail-label">Titular</span>
                        <span class="bank-detail-value">{{ $bankDetails['holder'] }}</span>
                    </div>
                    <div class="bank-detail-item">
                        <span class="bank-detail-label">Cédula / RIF</span>
                        <span class="bank-detail-value">{{ $bankDetails['document'] }}</span>
                    </div>
                    <div class="bank-detail-item">
                        <span class="bank-detail-label">Referencia</span>
                        <span class="bank-detail-value">Usa tu nombre + {{ $event->title }}</span>
                    </div>
                    <div class="bank-detail-item">
                        <span class="bank-detail-label">Monto</span>
                        <span class="bank-detail-value" id="payment-total">${{ number_format($event->price, 2) }}</span>
                    </div>
                </dl>
            </div>

            <div class="checkout-navigation">
                <button type="button" class="button button--outline prev-step" data-prev="2">Anterior</button>
                <button type="button" class="button button--rust next-step" data-next="4">Siguiente</button>
            </div>
        </div>

        <!-- Step 4: Payment Confirmation -->
        <div class="checkout-step" data-step="4" hidden>
            <p class="eyebrow">Paso 4 de 4</p>
            <h2>Confirmar pago</h2>
            <p class="form-intro">Adjunta el comprobante de transferencia para completar tu reserva.</p>

            <div class="admin-form-group">
                <label for="payment_reference">Referencia de pago *</label>
                <input type="text" id="payment_reference" name="payment_reference" required maxlength="100" placeholder="Ej. 1234567890">
                <p class="form-hint">El número de referencia que te dio tu banco al hacer la transferencia.</p>
            </div>

            <div class="admin-form-group">
                <label for="payment_date">Fecha de pago *</label>
                <input type="date" id="payment_date" name="payment_date" required max="{{ now()->format('Y-m-d') }}" value="{{ now()->format('Y-m-d') }}">
            </div>

            <div class="admin-form-group">
                <label for="proof">Comprobante *</label>
                <input type="file" id="proof" name="proof" accept="image/jpeg,image/png,application/pdf" required>
                <p class="form-hint">JPG, PNG o PDF. Máx. 5MB.</p>
            </div>

            <div class="checkout-navigation">
                <button type="button" class="button button--outline prev-step" data-prev="3">Anterior</button>
                <button type="submit" class="button button--rust" id="submit-booking">
                    <span aria-hidden="true">✓</span> Confirmar reserva
                </button>
            </div>
        </div>
    </form>
</dialog>

<!-- Success Modal -->
<dialog id="success-modal" class="booking-dialog animate__animated animate__fadeInUp">
    <div class="booking-form" style="text-align: center; padding: 48px 42px;">
        <button type="button" class="dialog-close" aria-label="Cerrar">×</button>

        <div class="success-icon animate__animated animate__zoomIn">✓</div>
        <h2 style="margin: 24px 0 12px;">¡Reserva enviada!</h2>
        <p class="form-intro" style="max-width: 360px; margin: 0 auto 24px;">Tu reserva ha sido registrada con el número <strong id="booking-reference"></strong>. Revisaremos el comprobante y te confirmaremos por correo.</p>

        <div style="margin-bottom: 24px; padding: 16px; background: #f0e7dc; border-radius: 2px; font-size: 12px;">
            <p style="margin: 0 0 8px;"><strong>Asistentes registrados:</strong></p>
            <ul id="success-attendees" style="margin: 0; padding-left: 20px; text-align: left;"></ul>
        </div>

        <button type="button" class="button button--rust" id="close-success">Cerrar</button>
    </div>
</dialog>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Countdown timer
    const timer = document.querySelector('[data-event-date]');
    if (timer) {
        const targetDate = new Date(timer.dataset.eventDate).getTime();

        const updateTimer = () => {
            const now = Date.now();
            const distance = targetDate - now;

            if (distance < 0) {
                timer.innerHTML = '<p class="eyebrow" style="color: var(--rust);">¡El evento ha comenzado!</p>';
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            timer.querySelector('[data-days]').textContent = String(days).padStart(2, '0');
            timer.querySelector('[data-hours]').textContent = String(hours).padStart(2, '0');
            timer.querySelector('[data-minutes]').textContent = String(minutes).padStart(2, '0');
            timer.querySelector('[data-seconds]').textContent = String(seconds).padStart(2, '0');
        };

        updateTimer();
        setInterval(updateTimer, 1000);
    }

    // Checkout Modal Logic
    const checkoutModal = document.getElementById('checkout-modal');
    const successModal = document.getElementById('success-modal');
    const openBtn = document.querySelector('[data-open-checkout]');
    const closeBtn = checkoutModal?.querySelector('.dialog-close');
    const form = document.getElementById('checkout-form');
    const steps = checkoutModal?.querySelectorAll('.checkout-step');
    const ticketQtyInput = document.getElementById('ticket-quantity');
    const attendeesContainer = document.getElementById('attendees-container');
    const summaryQty = document.getElementById('summary-qty');
    const summaryTotal = document.getElementById('summary-total');
    const paymentTotal = document.getElementById('payment-total');
    const availableTicketsEl = document.getElementById('available-tickets');
    const successAttendees = document.getElementById('success-attendees');
    const bookingReference = document.getElementById('booking-reference');
    const closeSuccess = document.getElementById('close-success');

    const EVENT_PRICE = {{ $event->price }};
    const MAX_TICKETS = {{ $event->available_tickets }};
    const EVENT_ID = {{ $event->id }};

    let currentStep = 1;
    let selectedTickets = 1;

    // Open modal
    openBtn?.addEventListener('click', () => {
        resetCheckout();
        checkoutModal?.showModal();
    });

    // Close modal
    closeBtn?.addEventListener('click', () => {
        checkoutModal?.close();
        resetCheckout();
    });

    checkoutModal?.addEventListener('click', (e) => {
        if (e.target === checkoutModal) {
            checkoutModal.close();
            resetCheckout();
        }
    });

    closeSuccess?.addEventListener('click', () => {
        successModal?.close();
    });

    successModal?.addEventListener('click', (e) => {
        if (e.target === successModal) successModal.close();
    });

    // Quantity buttons
    checkoutModal?.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const action = btn.dataset.action;
            const current = parseInt(ticketQtyInput.value);
            let next = current;

            if (action === 'increase' && current < MAX_TICKETS) next = current + 1;
            if (action === 'decrease' && current > 1) next = current - 1;

            ticketQtyInput.value = next;
            updateSummary(next);
        });
    });

    ticketQtyInput?.addEventListener('change', () => {
        let val = parseInt(ticketQtyInput.value);
        if (isNaN(val) || val < 1) val = 1;
        if (val > MAX_TICKETS) val = MAX_TICKETS;
        ticketQtyInput.value = val;
        updateSummary(val);
    });

    function updateSummary(qty) {
        selectedTickets = qty;
        summaryQty.textContent = qty;
        const total = (EVENT_PRICE * qty).toFixed(2);
        summaryTotal.textContent = '$' + total;
        paymentTotal.textContent = '$' + total;
    }

    // Navigation
    checkoutModal?.querySelectorAll('.next-step').forEach(btn => {
        btn.addEventListener('click', () => {
            const nextStep = parseInt(btn.dataset.next);
            if (validateStep(currentStep)) {
                goToStep(nextStep);
            }
        });
    });

    checkoutModal?.querySelectorAll('.prev-step').forEach(btn => {
        btn.addEventListener('click', () => {
            const prevStep = parseInt(btn.dataset.prev);
            goToStep(prevStep);
        });
    });

    function goToStep(step) {
        steps?.forEach(s => s.hidden = true);
        const targetStep = checkoutModal?.querySelector(`[data-step="${step}"]`);
        if (targetStep) {
            targetStep.hidden = false;
            currentStep = step;

            if (step === 2) {
                generateAttendeeFields(selectedTickets);
            }
        }
    }

    function validateStep(step) {
        if (step === 1) {
            if (!selectedTickets || selectedTickets < 1) {
                alert('Selecciona al menos 1 entrada');
                return false;
            }
            return true;
        }
        if (step === 2) {
            const inputs = attendeesContainer?.querySelectorAll('input[required]');
            for (const input of inputs || []) {
                if (!input.value.trim()) {
                    alert('Completa todos los campos de los asistentes');
                    input.focus();
                    return false;
                }
            }
            return true;
        }
        if (step === 3) {
            return true;
        }
        return true;
    }

    function generateAttendeeFields(count) {
        if (!attendeesContainer) return;

        attendeesContainer.innerHTML = '';

        for (let i = 1; i <= count; i++) {
            const fieldset = document.createElement('fieldset');
            fieldset.className = 'attendee-fieldset';
            fieldset.innerHTML = `
                <legend>Asistente ${i}</legend>
                <div class="admin-form-row">
                    <div class="admin-form-group">
                        <label>Nombre *</label>
                        <input type="text" name="attendees[${i-1}][first_name]" required maxlength="100" autocomplete="given-name">
                    </div>
                    <div class="admin-form-group">
                        <label>Apellido *</label>
                        <input type="text" name="attendees[${i-1}][last_name]" required maxlength="100" autocomplete="family-name">
                    </div>
                </div>
                <div class="admin-form-row">
                    <div class="admin-form-group">
                        <label>Cédula / DNI *</label>
                        <input type="text" name="attendees[${i-1}][document]" required maxlength="50" placeholder="Ej. V-12345678">
                    </div>
                    <div class="admin-form-group">
                        <label>Correo electrónico *</label>
                        <input type="email" name="attendees[${i-1}][email]" required maxlength="255" autocomplete="email">
                    </div>
                </div>
            `;
            attendeesContainer.appendChild(fieldset);
        }
    }

    function resetCheckout() {
        currentStep = 1;
        selectedTickets = 1;
        ticketQtyInput.value = 1;
        updateSummary(1);
        steps?.forEach((s, i) => s.hidden = i !== 0);
        attendeesContainer.innerHTML = '';
        form.reset();
    }

    // Form submit
    form?.addEventListener('submit', async (e) => {
        e.preventDefault();

        const submitBtn = document.getElementById('submit-booking');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner" style="display:inline-block;width:16px;height:16px;border:2px solid currentColor;border-right-color:transparent;border-radius:50%;animation:spin .8s linear infinite;margin-right:8px;vertical-align:middle;"></span>Procesando...';

        const formData = new FormData(form);
        formData.append('tickets', selectedTickets);

        try {
            const response = await fetch(`/events/${EVENT_ID}/checkout`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();

            if (data.success) {
                checkoutModal.close();
                resetCheckout();

                bookingReference.textContent = data.booking.reference;
                successAttendees.innerHTML = data.booking.attendees.map(a => `<li>${a.first_name} ${a.last_name} (${a.document})</li>`).join('');
                successModal.showModal();
            } else {
                alert(data.message || 'Error al procesar la reserva');
            }
        } catch (err) {
            alert('Error de conexión. Intenta nuevamente.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });

    // Add spinner animation
    const style = document.createElement('style');
    style.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
    document.head.appendChild(style);
});
</script>
@endpush