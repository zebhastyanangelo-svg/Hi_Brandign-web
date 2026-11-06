<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->paginate(10);

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create', [
            'paymentMethods' => $this->activePaymentMethods(),
        ]);
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
            'payment_methods' => ['nullable', 'array'],
            'payment_methods.*' => ['integer', Rule::exists('payment_methods', 'id')->where('is_active', true)],
            'is_featured' => ['boolean'],
            'is_published' => ['boolean'],
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $validated['image'] = $path;
        }

        $event = Event::create($validated);
        $event->paymentMethods()->sync($validated['payment_methods'] ?? []);

        return redirect()->route('admin.events.index')->with('success', 'Evento creado correctamente.');
    }

    public function show(Event $event)
    {
        $event->load(['bookings' => fn ($q) => $q->with(['attendees', 'paymentMethod'])->latest(), 'paymentMethods']);

        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $event->loadMissing('paymentMethods');

        return view('admin.events.edit', [
            'event' => $event,
            'paymentMethods' => $this->activePaymentMethods(),
        ]);
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
            'payment_methods' => ['nullable', 'array'],
            'payment_methods.*' => ['integer', Rule::exists('payment_methods', 'id')->where('is_active', true)],
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

        $event->update($validated);
        $event->paymentMethods()->sync($validated['payment_methods'] ?? []);

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

    /**
     * @return Collection<int, PaymentMethod>
     */
    private function activePaymentMethods(): Collection
    {
        return PaymentMethod::query()->active()->orderBy('sort_order')->orderBy('name')->get();
    }
}
