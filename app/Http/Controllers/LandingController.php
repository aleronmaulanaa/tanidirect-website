<?php

namespace App\Http\Controllers;

use App\Models\OrderPool;
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
        | Order Pool Terbaru (Patungan)
        |--------------------------------------------------------------------------
        */

        $featuredOrderPool = OrderPool::with('product.producer')
            ->where('status', 'open')
            ->latest()
            ->first();


        $groupBuyData = null;

        if ($featuredOrderPool) {

            $progress = min(
                100,
                ($featuredOrderPool->volume_terkumpul / max($featuredOrderPool->target_volume, 1)) * 100
            );

            $groupBuyData = [
                'orderPool' => $featuredOrderPool,
                'target' => $featuredOrderPool->target_volume,
                'current' => $featuredOrderPool->volume_terkumpul,
                'remaining' => max(0, $featuredOrderPool->target_volume - $featuredOrderPool->volume_terkumpul),
                'percentage' => round($progress),
            ];

        }




        return view('welcome', compact(

            'products',

            'priceReferences',

            'groupBuyData'

        ));

    }
}