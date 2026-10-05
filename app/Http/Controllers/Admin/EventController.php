<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:80'],
            'description' => ['required', 'string'],
            'place' => ['required', 'string', 'max:120'],
            'starts_at' => ['required', 'date'],
            'price' => ['required', 'numeric', 'min:0'],
            'capacity' => ['required', 'integer', 'min:1'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'bank_details.bank' => ['required', 'string', 'max:100'],
            'bank_details.account' => ['required', 'string', 'max:50'],
            'bank_details.holder' => ['required', 'string', 'max:100'],
            'bank_details.document' => ['required', 'string', 'max:50'],
            'is_featured' => ['boolean'],
            'is_published' => ['boolean'],
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $validated['image'] = $path;
        }

        $validated['bank_details'] = $request->input('bank_details');

        Event::create($validated);

        return redirect()->route('admin.events.index')->with('success', 'Evento creado correctamente.');
    }

    public function show(Event $event)
    {
        $event->load(['bookings' => fn($q) => $q->with('attendees')->latest()]);
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:80'],
            'description' => ['required', 'string'],
            'place' => ['required', 'string', 'max:120'],
            'starts_at' => ['required', 'date'],
            'price' => ['required', 'numeric', 'min:0'],
            'capacity' => ['required', 'integer', 'min:1'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'bank_details.bank' => ['required', 'string', 'max:100'],
            'bank_details.account' => ['required', 'string', 'max:50'],
            'bank_details.holder' => ['required', 'string', 'max:100'],
            'bank_details.document' => ['required', 'string', 'max:50'],
            'is_featured' => ['boolean'],
            'is_published' => ['boolean'],
        ]);

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $path = $request->file('image')->store('events', 'public');
            $validated['image'] = $path;
        }

        $validated['bank_details'] = $request->input('bank_details');

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Evento actualizado correctamente.');
    }

    public function destroy(Event $event)
    {
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Evento eliminado correctamente.');
    }
}