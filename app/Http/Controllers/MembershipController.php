<?php

namespace App\Http\Controllers;

use App\Models\IncomeNotification;
use App\Models\Membership;
use App\Models\PaymentMethod;
use App\Services\BcvRateService;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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

    public function checkout(string $slug, BcvRateService $rates): View
    {
        $membership = $this->membershipOptions()->firstWhere('slug', $slug);

        if ($membership === null) {
            abort(404);
        }

        $planIndex = (($membership->sort_order ?? 1) - 1) % 3 + 1;

        $paymentMethods = Schema::hasTable('payment_methods')
            ? PaymentMethod::query()->active()->orderBy('sort_order')->orderBy('name')->get()
            : collect();

        $rate = $rates->rate();

        return view('memberships.checkout', [
            'membership' => $membership,
            'planIndex' => $planIndex,
            'paymentMethods' => $paymentMethods,
            'bcvRate' => $rate,
            'amountVes' => round((float) $membership->price * $rate, 2),
        ]);
    }

    /**
     * Reporte de pago del cliente (multipart/form-data).
     * Alias público de processCheckout para el flujo del checkout.
     */
    public function notifyPayment(Request $request, string $slug, BcvRateService $rates)
    {
        return $this->processCheckout($request, $slug, $rates);
    }

    public function processCheckout(Request $request, string $slug, BcvRateService $rates)
    {
        $membership = $this->membershipOptions()->firstWhere('slug', $slug);

        if ($membership === null) {
            abort(404);
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:150'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'payment_method_id' => ['required', 'integer', Rule::exists('payment_methods', 'id')->where('is_active', true)],
            'bank_reference' => ['required', 'string', 'max:100'],
            'payment_date' => ['required', 'date', 'before_or_equal:today'],
            'account_holder_name' => ['required', 'string', 'max:150'],
            'account_holder_relationship' => ['required', Rule::in([IncomeNotification::RELATIONSHIP_OWN, IncomeNotification::RELATIONSHIP_THIRD])],
            'proof_path' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $paymentMethod = PaymentMethod::query()->findOrFail($validated['payment_method_id']);

        $rate = $rates->rate();
        $amountUsd = (float) $membership->price;
        $amountVes = round($amountUsd * $rate, 2);

        $proofPath = $request->file('proof_path')->store('payment-proofs', 'public');

        $notification = IncomeNotification::create([
            'reference' => 'PN-'.strtoupper(Str::random(8)),
            'membership_slug' => $membership->slug,
            'membership_name' => $membership->name,
            'amount_usd' => $amountUsd,
            'amount_ves' => $amountVes,
            'bcv_rate' => $rate,
            'payment_method_id' => $paymentMethod->id,
            'payment_method_name' => $paymentMethod->name,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'bank_reference' => $validated['bank_reference'],
            'payment_date' => $validated['payment_date'],
            'account_holder_name' => $validated['account_holder_name'],
            'account_holder_relationship' => $validated['account_holder_relationship'],
            'proof_path' => $proofPath,
            'status' => IncomeNotification::STATUS_PENDING,
        ]);

        return redirect()->route('memberships.checkout.confirmation', $notification->reference);
    }

    public function checkoutConfirmation(IncomeNotification $notification): View
    {
        return view('memberships.confirmation', ['notification' => $notification]);
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
