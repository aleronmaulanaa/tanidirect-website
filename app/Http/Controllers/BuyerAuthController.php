<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BuyerAuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'required|string|max:20',
            'kabupaten_kota' => 'required|string|max:100',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'kabupaten_kota' => $request->kabupaten_kota,
            'role' => 'pembeli',
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('buyer.login')
            ->with('success', 'Registrasi berhasil, silahkan login.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt([...$credentials, 'role' => 'pembeli'])) {

            $request->session()->regenerate();

            return redirect()
                ->route('buyer.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email, password, atau jenis akun tidak sesuai.',
        ]);
    }
}
