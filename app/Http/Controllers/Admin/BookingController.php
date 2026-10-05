<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
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
        $events = \App\Models\Event::orderBy('starts_at')->get();

        return view('admin.bookings.index', compact('bookings', 'events'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['event', 'attendees']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function approve(Booking $booking)
    {
        if ($booking->status !== 'pendiente') {
            return back()->with('error', 'Esta reserva ya ha sido procesada.');
        }

        $booking->update([
            'status' => 'aprobado',
            'approved_at' => Carbon::now(),
        ]);

        return back()->with('success', 'Pago aprobado. El asistente ha sido inscrito oficialmente.');
    }

    public function reject(Request $request, Booking $booking)
    {
        if ($booking->status !== 'pendiente') {
            return back()->with('error', 'Esta reserva ya ha sido procesada.');
        }

        $validated = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $booking->update([
            'status' => 'rechazado',
            'admin_notes' => $validated['admin_notes'],
        ]);

        return back()->with('success', 'Pago rechazado.');
    }
}