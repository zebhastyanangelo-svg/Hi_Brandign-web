<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentMethodController extends Controller
{
    public function index(): View
    {
        $paymentMethods = PaymentMethod::query()
            ->orderByDesc('is_active')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.payment-methods.index', [
            'paymentMethods' => $paymentMethods,
            'types' => PaymentMethod::typeLabels(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        PaymentMethod::create($validated + [
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => (int) $request->input('sort_order', 0),
        ]);

        return back()->with('success', 'Método de pago "'.$validated['name'].'" creado correctamente.');
    }

    public function edit(PaymentMethod $paymentMethod): View
    {
        return view('admin.payment-methods.edit', [
            'paymentMethod' => $paymentMethod,
            'types' => PaymentMethod::typeLabels(),
        ]);
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate($this->rules());

        $paymentMethod->update($validated + [
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => (int) $request->input('sort_order', 0),
        ]);

        return redirect()
            ->route('admin.payment-methods.index')
            ->with('success', 'Método de pago "'.$validated['name'].'" actualizado correctamente.');
    }

    public function toggle(Request $request, PaymentMethod $paymentMethod)
    {
        $paymentMethod->update(['is_active' => ! $paymentMethod->is_active]);

        $message = $paymentMethod->is_active
            ? 'Método de pago "'.$paymentMethod->name.'" activado. Ya es visible en el checkout.'
            : 'Método de pago "'.$paymentMethod->name.'" desactivado. Ya no aparece en el checkout.';

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message, 'is_active' => $paymentMethod->is_active]);
        }

        return back()->with('success', $message);
    }

    public function destroy(Request $request, PaymentMethod $paymentMethod)
    {
        $name = $paymentMethod->name;
        $paymentMethod->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Método de pago "'.$name.'" eliminado.']);
        }

        return back()->with('success', 'Método de pago "'.$name.'" eliminado correctamente.');
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'type' => ['required', 'string', 'max:40'],
            'bank' => ['nullable', 'string', 'max:120'],
            'tax_id' => ['nullable', 'string', 'max:50'],
            'account_number' => ['nullable', 'string', 'max:120'],
            'account_holder' => ['nullable', 'string', 'max:150'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
