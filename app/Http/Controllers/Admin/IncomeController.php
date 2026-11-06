<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\IncomeNotification;
use App\Models\MembershipOrder;
use App\Models\PaymentMethod;
use App\Services\BcvRateService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class IncomeController extends Controller
{
    public function index(Request $request, BcvRateService $rates): View
    {
        $tab = $request->string('tab')->toString() === 'pendientes' ? 'pendientes' : 'historial';

        $totals = $this->totals();
        $methods = PaymentMethod::query()->orderBy('sort_order')->orderBy('name')->get();

        $approved = $this->approvedPayments(
            $request->string('from')->toString(),
            $request->string('to')->toString(),
            $request->string('method')->toString(),
            $request->string('type')->toString(),
        );

        $pendingNotifications = IncomeNotification::query()
            ->where('status', IncomeNotification::STATUS_PENDING)
            ->with('paymentMethod')
            ->latest('payment_date')
            ->get();

        $pendingBookings = Booking::query()
            ->where('status', 'pendiente')
            ->with('event', 'attendees')
            ->latest()
            ->get();

        return view('admin.incomes.index', [
            'tab' => $tab,
            'totals' => $totals,
            'payments' => $approved,
            'pendingNotifications' => $pendingNotifications,
            'pendingBookings' => $pendingBookings,
            'methods' => $methods,
            'filters' => [
                'from' => $request->string('from')->toString(),
                'to' => $request->string('to')->toString(),
                'method' => $request->string('method')->toString(),
                'type' => $request->string('type')->toString(),
            ],
            'bcvRate' => $rates->rate(),
        ]);
    }

    public function approve(Request $request, IncomeNotification $notification)
    {
        if (! $notification->is_pending) {
            return back()->with('error', 'Esta notificación de pago ya fue procesada.');
        }

        $notification->update([
            'status' => IncomeNotification::STATUS_APPROVED,
            'processed_at' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Pago aprobado. La membresía del cliente quedó activa.']);
        }

        return back()->with('success', 'Pago aprobado. La membresía de '.$notification->customer_name.' quedó activa.');
    }

    public function reject(Request $request, IncomeNotification $notification)
    {
        if (! $notification->is_pending) {
            return back()->with('error', 'Esta notificación de pago ya fue procesada.');
        }

        $validated = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $notification->update([
            'status' => IncomeNotification::STATUS_REJECTED,
            'processed_at' => now(),
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);

        $message = 'Pago rechazado. Notificaremos a '.$notification->customer_name.' por correo.';

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return back()->with('success', $message);
    }

    /**
     * Ingresos totales: membresías + eventos (aprobados).
     *
     * @return array<string, float|int>
     */
    private function totals(): array
    {
        $memberships = (float) IncomeNotification::query()->where('status', IncomeNotification::STATUS_APPROVED)->sum('amount_usd')
            + (float) MembershipOrder::query()->where('status', MembershipOrder::STATUS_PAID)->sum('total');

        $events = (float) Booking::query()->where('status', 'aprobado')->sum('total_amount');

        return [
            'total' => $memberships + $events,
            'memberships' => $memberships,
            'events' => $events,
            'pending_count' => IncomeNotification::query()->where('status', IncomeNotification::STATUS_PENDING)->count()
                + Booking::query()->where('status', 'pendiente')->count(),
        ];
    }

    /**
     * Historial unificado de pagos aprobados (membresías y eventos)
     * con filtros por rango de fechas, método de pago y concepto.
     *
     * @return Collection<int, array<string, mixed>>
     */
    private function approvedPayments(string $from, string $to, string $method, string $type): Collection
    {
        $fromDate = $from !== '' ? $from : null;
        $toDate = $to !== '' ? $to : null;

        $payments = collect();

        if ($type === '' || $type === 'membresia') {
            IncomeNotification::query()
                ->where('status', IncomeNotification::STATUS_APPROVED)
                ->when($fromDate, fn ($query) => $query->whereDate('payment_date', '>=', $fromDate))
                ->when($toDate, fn ($query) => $query->whereDate('payment_date', '<=', $toDate))
                ->when($method !== '', fn ($query) => $query->where('payment_method_name', $method))
                ->latest('payment_date')
                ->get()
                ->each(fn (IncomeNotification $notification) => $payments->push([
                    'source' => 'notificacion',
                    'type' => 'membresia',
                    'type_label' => 'Membresía',
                    'reference' => $notification->reference,
                    'concept' => $notification->membership_name,
                    'client' => $notification->customer_name,
                    'client_email' => $notification->customer_email,
                    'method' => $notification->payment_method_name,
                    'amount' => (float) $notification->amount_usd,
                    'paid_at' => $notification->processed_at ?? $notification->created_at,
                    'status' => 'aprobado',
                ]));

            MembershipOrder::query()
                ->where('status', MembershipOrder::STATUS_PAID)
                ->when($fromDate, fn ($query) => $query->whereDate('created_at', '>=', $fromDate))
                ->when($toDate, fn ($query) => $query->whereDate('created_at', '<=', $toDate))
                ->latest()
                ->get()
                ->filter(fn (MembershipOrder $order) => $method === '' || $order->payment_method_label === $method || $order->payment_method === $method)
                ->each(fn (MembershipOrder $order) => $payments->push([
                    'source' => 'orden',
                    'type' => 'membresia',
                    'type_label' => 'Membresía',
                    'reference' => $order->reference,
                    'concept' => $order->membership_name,
                    'client' => $order->customer_name,
                    'client_email' => $order->customer_email,
                    'method' => $order->payment_method_label,
                    'amount' => (float) $order->total,
                    'paid_at' => $order->paid_at ?? $order->created_at,
                    'status' => 'aprobado',
                ]));
        }

        if ($type === '' || $type === 'evento') {
            Booking::query()
                ->where('status', 'aprobado')
                ->with('event', 'attendees', 'paymentMethod')
                ->when($fromDate, fn ($query) => $query->whereDate('created_at', '>=', $fromDate))
                ->when($toDate, fn ($query) => $query->whereDate('created_at', '<=', $toDate))
                ->latest()
                ->get()
                ->filter(function (Booking $booking) use ($method): bool {
                    if ($method === '') {
                        return true;
                    }

                    $bookingMethod = $booking->paymentMethod?->name ?? 'Transferencia';

                    return $bookingMethod === $method;
                })
                ->each(fn (Booking $booking) => $payments->push([
                    'source' => 'reserva',
                    'type' => 'evento',
                    'type_label' => 'Evento',
                    'reference' => $booking->reference,
                    'concept' => $booking->event?->title ?? 'Evento',
                    'client' => $booking->attendees->first()?->full_name ?? '—',
                    'client_email' => $booking->attendees->first()?->email ?? null,
                    'method' => $booking->paymentMethod?->name ?? 'Transferencia',
                    'amount' => (float) $booking->total_amount,
                    'paid_at' => $booking->approved_at ?? $booking->created_at,
                    'status' => 'aprobado',
                ]));
        }

        return $payments
            ->sortByDesc(fn (array $payment) => $payment['paid_at'])
            ->values();
    }
}
