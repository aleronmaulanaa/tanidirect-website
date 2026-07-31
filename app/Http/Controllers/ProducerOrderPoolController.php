<?php

namespace App\Http\Controllers;

use App\Models\OrderPool;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProducerOrderPoolController extends Controller
{
    public function index(Request $request): View
    {
        $profile = $request->user()->producerProfile;

        abort_unless($profile, 403, 'Profil produsen belum tersedia.');

        $orderPools = OrderPool::with('product')
            ->whereHas('product', fn ($q) => $q->where('producer_id', $profile->id))
            ->latest()
            ->paginate(10);

        return view('producer.order-pools.index', compact('orderPools'));
    }

    public function show(Request $request, OrderPool $orderPool): View
    {
        $this->ensureOwnership($request, $orderPool);

        $orderPool->load([
            'product.producer.user',
            'members.buyer',
            'orders.buyer',
        ]);

        return view('producer.order-pools.show', compact('orderPool'));
    }

    private function ensureOwnership(Request $request, OrderPool $orderPool): void
    {
        $profile = $request->user()->producerProfile;

        abort_unless($profile, 403);
        abort_unless(
            $orderPool->product->producer_id === $profile->id,
            403
        );
    }
}