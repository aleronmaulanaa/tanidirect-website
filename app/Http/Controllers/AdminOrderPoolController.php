<?php

namespace App\Http\Controllers;

use App\Models\OrderPool;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminOrderPoolController extends Controller
{
    public function index(Request $request): View
    {
        $orderPools = OrderPool::with('product.producer.user')
            ->latest()
            ->paginate(10);

        return view('admin.order-pools.index', compact('orderPools'));
    }

    public function show(OrderPool $orderPool): View
    {
        $orderPool->load([
            'product.producer.user',
            'members.buyer',
            'orders.buyer',
        ]);

        return view('admin.order-pools.show', compact('orderPool'));
    }
}