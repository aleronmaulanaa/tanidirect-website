<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user) {
            $user->load('producerProfile');
        }

        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user) {
            abort(401);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'kabupaten_kota' => ['nullable', 'string', 'max:100'],
            'lokasi_desa' => ['nullable', 'string', 'max:100'],
            'komoditas_utama' => ['nullable', 'string', 'max:100'],
        ]);

        $user->fill([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'kabupaten_kota' => $validated['kabupaten_kota'] ?? null,
        ]);
        $user->save();

        if ($user->role === 'produsen') {
            $profile = $user->producerProfile()->firstOrNew();
            $profile->fill([
                'kabupaten_kota' => $validated['kabupaten_kota'] ?? $user->kabupaten_kota,
                'lokasi_desa' => $validated['lokasi_desa'] ?? null,
                'komoditas_utama' => $validated['komoditas_utama'] ?? null,
            ]);
            $profile->save();
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
