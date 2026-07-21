<?php

namespace App\Http\Controllers;

use App\Models\OrderPool;
use App\Models\OrderPoolMember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderPoolController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $orderPools = OrderPool::query()
            ->with([
                'product.producer',
            ])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('product', function ($product) use ($search) {
                    $product->where('nama_produk', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('order-pool.index', [
            'orderPools' => $orderPools,
            'search' => $search,
            'status' => $status,
        ]);
    }

    public function show(OrderPool $orderPool): View
    {
        $orderPool->load([
            'product.producer',
            'members.buyer',
        ]);

        return view('order-pool.show', [
            'orderPool' => $orderPool,
        ]);
    }

    public function join(OrderPool $orderPool)
    {
        $user = Auth::user();

        $jumlah = request()->jumlah;


        if ($jumlah < 5) {

            return back()->with(
                'error',
                'Minimal pembelian adalah 5 kg.'
            );

        }


        $sisaVolume = $orderPool->target_volume - $orderPool->volume_terkumpul;


        if ($jumlah > $sisaVolume) {

            return back()->with(
                'error',
                'Jumlah pembelian melebihi sisa order pool.'
            );

        }


        $existing = OrderPoolMember::where('order_pool_id', $orderPool->id)
            ->where('buyer_id', $user->id)
            ->first();


        if ($existing) {

            return back()->with(
                'error',
                'Anda sudah bergabung pada order pool ini.'
            );

        }


        OrderPoolMember::create([
            'order_pool_id' => $orderPool->id,
            'buyer_id' => $user->id,
            'jumlah' => $jumlah,
        ]);


        $orderPool->increment(
            'volume_terkumpul',
            $jumlah
        );


        if (
            $orderPool->volume_terkumpul >= 
            $orderPool->target_volume
        ) {

            $orderPool->update([
                'status' => 'fulfilled'
            ]);

        }


        return back()->with(
            'success',
            'Berhasil bergabung dalam order pool.'
        );
    }
}