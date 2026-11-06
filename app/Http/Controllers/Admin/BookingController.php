<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['event', 'attendees'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        $bookings = $query->paginate(15)->withQueryString();
        $events = Event::orderBy('starts_at')->get();

        return view('admin.bookings.index', compact('bookings', 'events'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['event', 'attendees', 'paymentMethod']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function approve(Request $request, Booking $booking)
    {
        if ($booking->status !== 'pendiente') {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Esta reserva ya ha sido procesada.'], 422);
            }

            return back()->with('error', 'Esta reserva ya ha sido procesada.');
        }

        $booking->update([
            'status' => 'aprobado',
            'approved_at' => Carbon::now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Pago aprobado. El asistente ha sido inscrito oficialmente.']);
        }

        return back()->with('success', 'Pago aprobado. El asistente ha sido inscrito oficialmente.');
    }

    public function reject(Request $request, Booking $booking)
    {
        if ($booking->status !== 'pendiente') {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Esta reserva ya ha sido procesada.'], 422);
            }

            return back()->with('error', 'Esta reserva ya ha sido procesada.');
        }

        $validated = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $booking->update([
            'status' => 'rechazado',
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Pago rechazado.']);
        }

        return back()->with('success', 'Pago rechazado.');
    }
}
