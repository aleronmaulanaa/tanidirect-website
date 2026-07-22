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
            ->limit(4)
            ->get();



        /*
        |--------------------------------------------------------------------------
        | Harga Referensi Pasar
        |--------------------------------------------------------------------------
        */

        $komoditasUnggulan = [
            'BERAS MEDIUM',
            'BERAS PREMIUM',
            'JAGUNG PIPIL KERING',
            'KEDELAI',
        ];


        $priceReferences = collect();



        foreach ($komoditasUnggulan as $komoditas) {


            $produsen = PriceReference::where(
                    'kategori_komoditas',
                    $komoditas
                )
                ->where('tipe_harga', 'produsen')
                ->where('harga', '>', 0)
                ->orderByDesc('periode')
                ->first();



            $konsumen = PriceReference::where(
                    'kategori_komoditas',
                    $komoditas
                )
                ->where('tipe_harga', 'konsumen')
                ->where('harga', '>', 0)
                ->orderByDesc('periode')
                ->first();



            if ($produsen) {

                $priceReferences->push([

                    'kategori' => $komoditas,

                    'harga_produsen' => $produsen->harga,

                    'harga_konsumen' => $konsumen?->harga,

                    'periode' => $produsen->periode,

                ]);

            }

        }




        /*
        |--------------------------------------------------------------------------
        | Group Buy Preview
        |--------------------------------------------------------------------------
        |
        | Sementara menggunakan produk aktif.
        | Nanti diganti dengan order_pools.volume_terkumpul
        |
        |--------------------------------------------------------------------------
        */


        $groupBuyData = null;


        $groupBuyProduct = Product::where('is_active', true)
            ->latest()
            ->first();



        if ($groupBuyProduct) {


            $target = 30;


            $current = min(
                round($groupBuyProduct->stok * 0.4),
                $target
            );


            $percentage = round(
                ($current / $target) * 100
            );



            $groupBuyData = [

                'product' => $groupBuyProduct,

                'target' => $target,

                'current' => $current,

                'remaining' => $target - $current,

                'percentage' => $percentage,

            ];

        }




        return view('welcome', compact(

            'products',

            'priceReferences',

            'groupBuyData'

        ));

    }
}