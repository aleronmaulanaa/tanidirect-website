<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PriceReference;

class LandingController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Produk Aktif dari Petani
        |--------------------------------------------------------------------------
        */
        $products = Product::with('producer')
            ->where('is_active', true)
            ->latest()
            ->limit(6)
            ->get();



        /*
        |--------------------------------------------------------------------------
        | Harga Referensi Komoditas
        |--------------------------------------------------------------------------
        |
        | Digunakan ketika belum ada produk petani.
        | Data berasal dari BPS dan Siskaperbapo.
        |
        |--------------------------------------------------------------------------
        */

        $priceReferences = collect();


        if ($products->isEmpty()) {


            $komoditasUnggulan = [
                'BERAS MEDIUM',
                'BERAS PREMIUM',
                'JAGUNG PIPIL KERING',
                'KEDELAI',
            ];



            foreach ($komoditasUnggulan as $komoditas) {


                /*
                |--------------------------------------------------------------------------
                | Harga Produsen (Petani)
                |--------------------------------------------------------------------------
                */

                $produsen = PriceReference::where(
                        'kategori_komoditas',
                        $komoditas
                    )
                    ->where('tipe_harga', 'produsen')
                    ->where('harga', '>', 0)
                    ->orderByDesc('periode')
                    ->first();
                /*
                |--------------------------------------------------------------------------
                | Harga Konsumen (Pasar)
                |--------------------------------------------------------------------------
                */

                $konsumen = PriceReference::where(
                        'kategori_komoditas',
                        $komoditas
                    )
                    ->where('tipe_harga', 'konsumen')
                    ->where('harga', '>', 0)
                    ->orderByDesc('periode')
                    ->first();

                if ($produsen) {


                    $hargaProdusen = (float) $produsen->harga;

                    $hargaKonsumen = $konsumen
                        ? (float) $konsumen->harga
                        : null;



                    $selisih = null;


                    if ($hargaKonsumen) {

                        $selisih = round(
                            (($hargaKonsumen - $hargaProdusen) / $hargaKonsumen) * 100
                        );

                    }



                    $priceReferences->push([

                        'kategori' => $komoditas,

                        'harga_produsen' => $hargaProdusen,

                        'harga_konsumen' => $hargaKonsumen,

                        'selisih_harga' => $selisih,

                        'periode' => $produsen->periode,

                    ]);

                }

            }

        }



        return view('welcome', compact(
            'products',
            'priceReferences'
        ));
    }
}