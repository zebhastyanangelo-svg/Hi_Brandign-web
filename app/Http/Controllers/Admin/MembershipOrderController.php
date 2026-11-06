<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipOrder;
use Illuminate\Http\Request;

class MembershipOrderController extends Controller
{
    public function approve(Request $request, MembershipOrder $order)
    {
        if (! $order->is_processing) {
            return back()->with('error', 'Este pago ya ha sido procesado.');
        }

        $order->update([
            'status' => MembershipOrder::STATUS_PAID,
            'paid_at' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Pago de membresía confirmado.']);
        }

        return back()->with('success', 'Pago de membresía confirmado. El socio quedó activo.');
    }

    public function reject(Request $request, MembershipOrder $order)
    {
        if (! $order->is_processing) {
            return back()->with('error', 'Este pago ya ha sido procesado.');
        }

        $validated = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $order->update([
            'status' => MembershipOrder::STATUS_REJECTED,
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);

        $message = 'Pago de membresía rechazado.';

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return back()->with('success', $message);
    }
}
