<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

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
}
