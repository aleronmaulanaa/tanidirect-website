<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProducerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class ProducerAuthController extends Controller
{

    public function register(Request $request)
    {

        $request->validate([

            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'kabupaten_kota' => 'required',
            'komoditas_utama' => 'required',
            'password' => 'required|min:6',

        ]);


        $user = User::create([

            'name' => $request->name,

            'email' => $request->email,

            'role' => 'produsen',

            'phone' => $request->phone,

            'kabupaten_kota' => $request->kabupaten_kota,

            'password' => Hash::make($request->password),

        ]);



        ProducerProfile::create([

            'user_id' => $user->id,

            'komoditas_utama' => $request->komoditas_utama,
            
            'kabupaten_kota' => $request->kabupaten_kota,

            'status_verifikasi' => 'terverifikasi',

        ]);



        // setelah register langsung ke login
        return redirect()
            ->route('producer.login')
            ->with('success', 'Registrasi berhasil. Silakan login.');

    }



    public function login(Request $request)
    {

        $credentials = $request->validate([

            'email'=>'required|email',

            'password'=>'required',

        ]);



        if(Auth::attempt($credentials)){


            $request->session()->regenerate();


            $user = Auth::user();



            if($user->role !== 'produsen'){

                Auth::logout();

                return back()->withErrors([

                    'email'=>'Akun ini bukan akun petani.'

                ]);

            }



            return redirect()
                ->route('producer.dashboard');

        }

        return back()->withErrors([

            'email'=>'Email atau password salah.'

        ]);

    }

    public function logout(Request $request)
    {

        Auth::logout();


        $request->session()->invalidate();


        $request->session()->regenerateToken();


        return redirect()
            ->route('producer.login')
            ->with('success', 'Berhasil logout.');

    }


}