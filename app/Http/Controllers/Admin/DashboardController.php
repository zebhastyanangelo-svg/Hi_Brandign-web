<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Event;
use App\Models\IncomeNotification;
use App\Models\Membership;
use App\Models\MembershipOrder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'active_memberships' => MembershipOrder::where('status', MembershipOrder::STATUS_PAID)->count()
                + IncomeNotification::where('status', IncomeNotification::STATUS_APPROVED)->count(),
            'upcoming_events' => Event::where('is_published', true)->where('starts_at', '>', now())->count(),
            'pending_payments' => Booking::where('status', 'pendiente')->count()
                + MembershipOrder::where('status', MembershipOrder::STATUS_PROCESSING)->count()
                + IncomeNotification::where('status', IncomeNotification::STATUS_PENDING)->count(),
        ];

        $events = Event::query()->orderBy('starts_at')->take(6)->get();
        $memberships = Schema::hasTable('memberships')
            ? Membership::query()->orderBy('sort_order')->get()
            : collect();
        $payments = $this->recentPayments();

        return view('admin.dashboard', compact('stats', 'events', 'memberships', 'payments'));
    }

    /**
     * Últimos pagos recibidos (notificaciones de membresía, órdenes y reservas de eventos).
     *
     * @return Collection<int, array<string, mixed>>
     */
    private function recentPayments(): Collection
    {
        $orders = MembershipOrder::query()->latest()->take(10)->get()
            ->map(fn (MembershipOrder $order) => [
                'type' => 'membresia',
                'reference' => $order->reference,
                'concept' => $order->membership_name,
                'client' => $order->customer_name,
                'method' => $order->payment_method_label,
                'amount' => (float) $order->total,
                'status' => $order->status,
                'status_label' => $this->statusLabel($order->status),
                'date' => $order->created_at,
                'approve_route' => $order->is_processing ? route('admin.membership-orders.approve', $order) : null,
                'reject_route' => $order->is_processing ? route('admin.membership-orders.reject', $order) : null,
                'view_route' => null,
            ]);

        $notifications = IncomeNotification::query()->latest()->take(10)->get()
            ->map(fn (IncomeNotification $notification) => [
                'type' => 'membresia',
                'reference' => $notification->reference,
                'concept' => $notification->membership_name,
                'client' => $notification->customer_name,
                'method' => $notification->payment_method_name,
                'amount' => (float) $notification->amount_usd,
                'status' => $notification->status,
                'status_label' => $this->statusLabel($notification->status),
                'date' => $notification->created_at,
                'approve_route' => $notification->is_pending ? route('admin.incomes.approve', $notification) : null,
                'reject_route' => $notification->is_pending ? route('admin.incomes.reject', $notification) : null,
                'view_route' => null,
            ]);

        $bookings = Booking::query()->with('event', 'attendees')->latest()->take(10)->get()
            ->map(fn (Booking $booking) => [
                'type' => 'evento',
                'reference' => $booking->reference,
                'concept' => $booking->event?->title ?? 'Evento',
                'client' => $booking->attendees->first()?->full_name ?? '—',
                'method' => 'Transferencia',
                'amount' => (float) $booking->total_amount,
                'status' => $booking->status,
                'status_label' => $this->statusLabel($booking->status),
                'date' => $booking->created_at,
                'approve_route' => $booking->is_pending ? route('admin.bookings.approve', $booking) : null,
                'reject_route' => $booking->is_pending ? route('admin.bookings.reject', $booking) : null,
                'view_route' => route('admin.bookings.show', $booking),
            ]);

        return $orders->merge($notifications)->merge($bookings)
            ->sortByDesc(fn (array $payment) => $payment['date'])
            ->take(8)
            ->values();
    }

    private function statusLabel(string $status): string
    {
        return match ($status) {
            MembershipOrder::STATUS_PAID, IncomeNotification::STATUS_APPROVED, 'aprobado' => 'Pagado',
            MembershipOrder::STATUS_PROCESSING, IncomeNotification::STATUS_PENDING, 'pendiente' => 'Procesando',
            MembershipOrder::STATUS_REJECTED, IncomeNotification::STATUS_REJECTED, 'rechazado' => 'Rechazado',
            default => ucfirst($status),
        };
    }
}
