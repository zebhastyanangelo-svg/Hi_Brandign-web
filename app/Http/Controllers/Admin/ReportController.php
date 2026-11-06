<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\IncomeNotification;
use App\Models\MembershipOrder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function payments(): StreamedResponse
    {
        $fileName = 'reporte-pagos-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () {
            $output = fopen('php://output', 'w');

            fputcsv($output, ['Fecha', 'Referencia', 'Tipo', 'Concepto', 'Cliente', 'Método de pago', 'Monto', 'Estado']);

            IncomeNotification::query()->latest()->chunk(100, function ($notifications) use ($output) {
                foreach ($notifications as $notification) {
                    fputcsv($output, [
                        $notification->created_at->format('Y-m-d H:i'),
                        $notification->reference,
                        'Membresía',
                        $notification->membership_name,
                        $notification->customer_name,
                        $notification->payment_method_name,
                        number_format((float) $notification->amount_usd, 2, '.', ''),
                        $this->statusLabel($notification->status),
                    ]);
                }
            });

            MembershipOrder::query()->latest()->chunk(100, function ($orders) use ($output) {
                foreach ($orders as $order) {
                    fputcsv($output, [
                        $order->created_at->format('Y-m-d H:i'),
                        $order->reference,
                        'Membresía',
                        $order->membership_name,
                        $order->customer_name,
                        $order->payment_method_label,
                        number_format((float) $order->total, 2, '.', ''),
                        $this->statusLabel($order->status),
                    ]);
                }
            });

            Booking::query()->with('event', 'attendees', 'paymentMethod')->latest()->chunk(100, function ($bookings) use ($output) {
                foreach ($bookings as $booking) {
                    fputcsv($output, [
                        $booking->created_at->format('Y-m-d H:i'),
                        $booking->reference,
                        'Evento',
                        $booking->event?->title ?? 'Evento',
                        $booking->attendees->first()?->full_name ?? '—',
                        $booking->paymentMethod?->name ?? 'Transferencia',
                        number_format((float) $booking->total_amount, 2, '.', ''),
                        $this->statusLabel($booking->status),
                    ]);
                }
            });

            fclose($output);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
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
