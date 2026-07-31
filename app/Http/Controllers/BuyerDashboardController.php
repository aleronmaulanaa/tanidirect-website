<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderPool;
use App\Models\PriceReference;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class BuyerDashboardController extends Controller
{
    public function index()
    {
        $buyer = Auth::user();



        /*
        |--------------------------------------------------------------------------
        | Produk Aktif Dari Petani
        |--------------------------------------------------------------------------
        */

        $products = Product::with('producer.user')
            ->where('is_active', true)
            ->latest()
            ->limit(4)
            ->get();





        /*
        |--------------------------------------------------------------------------
        | Referensi Harga Pasar
        |--------------------------------------------------------------------------
        |
        | Selalu ditampilkan sebagai pembanding harga, terlepas dari ada atau
        | tidaknya produk yang dijual produsen.
        |
        */

        $priceReferences = collect();


        $komoditasUnggulan = [

                'BERAS MEDIUM',

                'BERAS PREMIUM',

                'JAGUNG PIPIL KERING',

                'KEDELAI',

        ];



        foreach ($komoditasUnggulan as $komoditas) {


                $hargaProdusen = PriceReference::where(
                        'kategori_komoditas',
                        $komoditas
                    )
                    ->where('tipe_harga', 'produsen')
                    ->where('harga', '>', 0)
                    ->latest('periode')
                    ->first();



                $hargaKonsumen = PriceReference::where(
                        'kategori_komoditas',
                        $komoditas
                    )
                    ->where('tipe_harga', 'konsumen')
                    ->where('harga', '>', 0)
                    ->latest('periode')
                    ->first();



            if ($hargaProdusen) {


                    $priceReferences->push([

                        'kategori' => $komoditas,

                        'harga_produsen' => $hargaProdusen->harga,

                        'harga_konsumen' => $hargaKonsumen?->harga,

                        'periode' => $hargaProdusen->periode,

                    ]);

            }

        }






        /*
        |--------------------------------------------------------------------------
        | Patungan Aktif
        |--------------------------------------------------------------------------
        */

        $orderPools = OrderPool::with('product.producer')
            ->where('status', 'open')
            ->latest()
            ->limit(3)
            ->get();






        /*
        |--------------------------------------------------------------------------
        | Pesanan Buyer
        |--------------------------------------------------------------------------
        */

        $orders = Order::with('product')
            ->where('buyer_id', $buyer->id)
            ->latest()
            ->limit(3)
            ->get();






        /*
        |--------------------------------------------------------------------------
        | Statistik Dashboard
        |--------------------------------------------------------------------------
        */

        $totalProducts = Product::where('is_active', true)
            ->count();



        $totalOrderPools = OrderPool::where('status', 'open')
            ->count();



        $totalOrders = Order::where('buyer_id', $buyer->id)
            ->count();







        return view('buyer.dashboard', compact(

            'buyer',

            'products',

            'priceReferences',

            'orderPools',

            'orders',

            'totalProducts',

            'totalOrderPools',

            'totalOrders'

        ));

    }
}
