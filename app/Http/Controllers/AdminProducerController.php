<?php

namespace App\Http\Controllers;

use App\Models\ProducerProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminProducerController extends Controller
{
    /**
     * Menampilkan daftar produsen (petani) beserta status verifikasinya.
     */
    public function index(): View
    {
        $producers = User::where('role', 'produsen')
            ->with('producerProfile')
            ->latest()
            ->paginate(10);

        return view('admin.producers.index', compact('producers'));
    }

    /**
     * Verifikasi produsen menjadi 'terverifikasi'.
     */
    public function verify(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->role === 'produsen', 404, 'Pengguna bukan produsen.');

        $profile = $user->producerProfile;

        if (! $profile) {
            $profile = ProducerProfile::create([
                'user_id'           => $user->id,
                'kabupaten_kota'    => $user->kabupaten_kota ?? '-',
                'komoditas_utama'   => '-',
                'status_verifikasi' => 'menunggu',
            ]);
        }

        $profile->update([
            'status_verifikasi' => 'terverifikasi',
        ]);

        return redirect()->route('admin.producers.index')
            ->with('success', 'Produsen "' . $user->name . '" telah berhasil diverifikasi.');
    }
}
