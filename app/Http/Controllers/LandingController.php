<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PriceReference;

class LandingController extends Controller
{
public function index()
{
    $products = Product::with('producer')
        ->where('is_active', true)
        ->latest()
        ->limit(4)
        ->get();


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


    return view('welcome', compact(
        'products',
        'priceReferences'
    ));
}
}