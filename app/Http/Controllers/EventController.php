<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public function index()
    {
        $events = Schema::hasTable('events')
            ? Event::query()->where('is_published', true)->orderBy('starts_at')->get()
            : collect();

        if ($events->isEmpty()) {
            $events = collect([
                ['title' => 'Founder Dinner & Demo Night', 'category' => 'CENA · NETWORKING', 'description' => 'Una mesa íntima para compartir lo que estamos construyendo y conocer a quienes vienen a cambiar las reglas.', 'place' => 'Casa Hi, Polanco', 'starts_at' => Carbon::parse('2026-10-09 19:00'), 'is_featured' => true],
                ['title' => 'Construir una marca que perdure', 'category' => 'WORKSHOP · BRAND', 'description' => 'Una sesión práctica sobre estrategia, voz y decisiones de diseño con intención.', 'place' => 'Estudio Norte', 'starts_at' => Carbon::parse('2026-10-16 10:30'), 'is_featured' => false],
                ['title' => 'Capital con propósito', 'category' => 'CONVERSACIÓN · CAPITAL', 'description' => 'Fundadores e inversionistas conversan sobre crecimiento sostenible y nuevas formas de financiarlo.', 'place' => 'Casa Hi, Polanco', 'starts_at' => Carbon::parse('2026-10-23 18:00'), 'is_featured' => false],
                ['title' => 'Product office hours', 'category' => 'CLÍNICA · PRODUCTO', 'description' => 'Trae tu reto de producto y trabajémoslo con líderes que ya recorrieron ese camino.', 'place' => 'Sala Estudio', 'starts_at' => Carbon::parse('2026-10-30 12:00'), 'is_featured' => false],
            ])->map(fn (array $event) => (object) $event);
        }

        $featuredEvent = $events->firstWhere('is_featured', true) ?? $events->first();
        $upcomingEvents = $events->reject(fn ($event) => $event === $featuredEvent)->values();

        return view('events.index', compact('featuredEvent', 'upcomingEvents'));
    }

    public function show(Event $event)
    {
        if (!$event->is_published) {
            abort(404);
        }

        $bankDetails = $event->bank_details_array;

        return view('events.show', compact('event', 'bankDetails'));
    }

    public function checkout(Request $request, Event $event)
    {
        if (!$event->is_published) {
            abort(404);
        }

        $validated = $request->validate([
            'tickets' => ['required', 'integer', 'min:1', 'max:' . $event->available_tickets],
            'attendees' => ['required', 'array', 'size:' . $request->input('tickets')],
            'attendees.*.first_name' => ['required', 'string', 'max:100'],
            'attendees.*.last_name' => ['required', 'string', 'max:100'],
            'attendees.*.document' => ['required', 'string', 'max:50'],
            'attendees.*.email' => ['required', 'email', 'max:255'],
            'payment_reference' => ['required', 'string', 'max:100'],
            'payment_date' => ['required', 'date', 'before_or_equal:today'],
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $proof = $request->file('proof');
        $proofPath = $proof->store('booking-proofs/' . $event->id, 'public');

        $booking = Booking::create([
            'event_id' => $event->id,
            'reference' => 'BK-' . strtoupper(Str::random(8)),
            'total_amount' => $event->price * $validated['tickets'],
            'tickets_count' => $validated['tickets'],
            'status' => 'pendiente',
            'payment_reference' => $validated['payment_reference'],
            'payment_date' => $validated['payment_date'],
            'proof_path' => $proofPath,
        ]);

        foreach ($validated['attendees'] as $attendeeData) {
            $booking->attendees()->create($attendeeData);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tu reserva ha sido enviada para revisión. Te notificaremos cuando sea aprobada.',
            'booking' => $booking->load('attendees'),
        ]);
    }
}