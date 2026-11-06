<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function update(Request $request, Membership $membership)
    {
        $validated = $request->validate([
            'price' => ['required', 'integer', 'min:0', 'max:9999999'],
            'capacity' => ['nullable', 'integer', 'min:0', 'max:99999'],
            'featured' => ['boolean'],
        ]);

        $membership->update([
            'price' => $validated['price'],
            'capacity' => $validated['capacity'] ?? null,
            'featured' => $validated['featured'] ?? false,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tarifas y cupos actualizados.',
                'membership' => $membership->fresh()->only(['id', 'price', 'capacity', 'featured']),
            ]);
        }

        return back()->with('success', 'Membresía actualizada correctamente.');
    }
}
