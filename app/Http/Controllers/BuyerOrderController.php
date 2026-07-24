<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ShipmentStatusLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BuyerOrderController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'jumlah' => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($request, $product, $data) {
            $product = Product::query()->lockForUpdate()->findOrFail($product->id);

            if (! $product->is_active) {
                throw ValidationException::withMessages([
                    'jumlah' => 'Produk ini sudah tidak tersedia.',
                ]);
            }

            if ($data['jumlah'] > $product->stok) {
                throw ValidationException::withMessages([
                    'jumlah' => "Stok tidak mencukupi. Stok tersedia: {$product->stok} kg.",
                ]);
            }

            $subtotal = $product->harga_jual * $data['jumlah'];

            $order = Order::create([
                'buyer_id' => $request->user()->id,
                'product_id' => $product->id,
                'jumlah' => $data['jumlah'],
                'total_harga' => $subtotal,
                'subtotal' => $subtotal,
                'service_fee' => 0,
                'grand_total' => $subtotal,
                'status_pengiriman' => 'dipesan',
            ]);

            ShipmentStatusLog::create([
                'order_id' => $order->id,
                'status' => 'dipesan',
                'catatan' => 'Pesanan langsung berhasil dibuat oleh pembeli.',
                'diperbarui_pada' => now(),
            ]);

            $product->decrement('stok', $data['jumlah']);
        });

        return redirect()->route('buyer.dashboard', fragment: 'pesanan')
            ->with('success', 'Pesanan berhasil dibuat. Petani akan memproses pesanan Anda.');
    }
}
