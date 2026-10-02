<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class MembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('home', ['memberships' => $this->membershipOptions()]);
    }

    public function plans(): View
    {
        return view('memberships.index', ['memberships' => $this->membershipOptions()]);
    }

    private function membershipOptions(): EloquentCollection|Collection
    {
        $memberships = Schema::hasTable('memberships')
            ? Membership::query()->orderBy('sort_order')->get()
            : collect();

        if ($memberships->isNotEmpty()) {
            return $memberships;
        }

        return collect([
            (object) ['slug' => 'class-sala', 'name' => 'Class Sala', 'description' => 'Ideal para eventos corporativos, reuniones grupales y talleres de alto nivel.', 'price' => 450, 'billing_period' => 'mes', 'featured' => false, 'features' => ['85” Smart TV & Audio Pro', 'Wi-Fi de alta velocidad', 'Seguridad y acceso privado'], 'capacity' => 'Capacidad 25–30'],
            (object) ['slug' => 'pass-ejecutivo', 'name' => 'Pass Ejecutivo', 'description' => 'Pensado para profesionales que buscan un entorno elegante, conectivo y flexible.', 'price' => 180, 'billing_period' => 'mes', 'featured' => true, 'features' => ['Opciones por día y medio día', 'Acceso exclusivo a Lobby & Lounge', 'Café de especialidad'], 'capacity' => 'Flexible'],
            (object) ['slug' => 'medical-suma', 'name' => 'Medical Suma', 'description' => 'Un espacio clínico y ejecutivo para consultas especializadas y telemedicina.', 'price' => 320, 'billing_period' => 'mes', 'featured' => false, 'features' => ['Mesa ejecutiva de alta gama', 'Pantalla para presentaciones', 'Acceso controlado'], 'capacity' => 'Capacidad 12'],
        ]);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Membership $membership)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Membership $membership)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Membership $membership)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Membership $membership)
    {
        //
    }
}
