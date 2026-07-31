<?php

namespace App\Http\Controllers;

use App\Models\ProducerProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProducerAuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'kabupaten_kota' => 'required',
            'komoditas_utama' => 'required',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => 'produsen',
            'phone' => $validated['phone'],
            'kabupaten_kota' => $validated['kabupaten_kota'],
            'password' => Hash::make($validated['password']),
        ]);

        ProducerProfile::create([
            'user_id' => $user->id,
            'kabupaten_kota' => $validated['kabupaten_kota'],
            'komoditas_utama' => $validated['komoditas_utama'],
            'status_verifikasi' => 'menunggu',
        ]);

        return redirect()
            ->route('producer.login')
            ->with('success', 'Registrasi berhasil, silahkan login.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt([...$credentials, 'role' => 'produsen'])) {

            $request->session()->regenerate();

            return redirect()
                ->route('producer.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email, password, atau jenis akun tidak sesuai.',
        ]);
    }
}
