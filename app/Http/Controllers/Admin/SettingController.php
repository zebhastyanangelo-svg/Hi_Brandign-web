<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings', ['adminUser' => Auth::guard('admin')->user()]);
    }

    public function updatePassword(Request $request)
    {
        $adminUser = Auth::guard('admin')->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($validated['current_password'], $adminUser->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'La contraseña actual no coincide con nuestros registros.',
            ]);
        }

        $adminUser->update([
            'password' => $validated['password'],
        ]);

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }
}
