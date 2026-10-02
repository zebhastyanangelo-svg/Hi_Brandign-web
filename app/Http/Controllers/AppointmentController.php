<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = collect();
        $history = collect();

        if (Schema::hasTable('appointments')) {
            $appointments = Appointment::query()
                ->where('scheduled_at', '>=', now())
                ->orderBy('scheduled_at')
                ->get();
            $history = Appointment::query()
                ->where('scheduled_at', '<', now())
                ->latest('scheduled_at')
                ->take(4)
                ->get();
        }

        return view('appointments.index', compact('appointments', 'history'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'focus' => ['required', 'string', 'max:80'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'meeting_type' => ['required', 'in:in-person,video'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        Appointment::create($validated);

        return redirect()->route('appointments.index')->with('status', 'Tu solicitud quedó agendada. Nos pondremos en contacto contigo pronto.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        //
    }
}
