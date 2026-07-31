<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\ShipmentStatusLog;
use App\Notifications\NewOrderCreated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Midtrans\Transaction;

class BuyerOrderController extends Controller
{
    private function setupMidtrans(): void
    {
        MidtransConfig::$serverKey = config('services.midtrans.server_key');
        MidtransConfig::$isProduction = filter_var(config('services.midtrans.is_production'), FILTER_VALIDATE_BOOLEAN);
        MidtransConfig::$isSanitized = true;
        MidtransConfig::$is3ds = true;
    }

    /**
     * Halaman checkout Midtrans — generate Snap token pembayaran.
     */
    public function checkout(Request $request, Product $product)
    {
        $data = $request->validate([
            'jumlah' => ['required', 'integer', 'min:1'],
        ]);

        abort_unless($product->is_active, 404, 'Produk tidak tersedia.');

        $quantity = $data['jumlah'];

        if ($quantity > $product->stok) {
            return back()->withErrors(['jumlah' => "Stok tidak mencukupi. Stok tersedia: {$product->stok} kg."]);
        }

        if (! config('services.midtrans.server_key') || ! config('services.midtrans.client_key')) {
            abort(500, 'Konfigurasi Midtrans belum diatur. Harap tambahkan MIDTRANS_SERVER_KEY dan MIDTRANS_CLIENT_KEY di file .env.');
        }

        $pricePerUnit = $product->harga_jual;
        $subtotal = $pricePerUnit * $quantity;
        $serviceFee = max(0, round($subtotal * 0.02, 2));
        $grandTotal = $subtotal + $serviceFee;
        $orderId = 'TANI-'.time().'-'.rand(1000, 9999);

        $this->setupMidtrans();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grandTotal,
            ],
            'item_details' => [
                [
                    'id' => $product->id,
                    'price' => $product->harga_jual,
                    'quantity' => $quantity,
                    'name' => $product->nama_produk,
                ],
            ],
            'customer_details' => [
                'first_name' => $request->user()->name,
                'email' => $request->user()->email,
            ],
            'enabled_payments' => [
                'gopay',
                'bank_transfer',
                'credit_card',
                'bca_va',
                'bni_va',
                'permata_va',
                'bri_va',
                'cimb_va',
                'ovo',
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('buyer.products.checkout', compact(
            'product',
            'quantity',
            'subtotal',
            'serviceFee',
            'grandTotal',
            'snapToken'
        ));
    }

    /**
     * Proses pembuatan pesanan setelah transaksi Midtrans diverifikasi.
     */
    public function store(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'jumlah' => ['required', 'integer', 'min:1'],
            'midtrans_order_id' => ['required', 'string'],
            'midtrans_transaction_id' => ['required', 'string'],
            'midtrans_payment_type' => ['required', 'string'],
            'midtrans_payment_status' => ['required', 'string'],
        ]);

        $this->setupMidtrans();

        try {
            $transaction = Transaction::status($data['midtrans_order_id']);
        } catch (\Exception $exception) {
            throw ValidationException::withMessages([
                'jumlah' => 'Tidak dapat memverifikasi pembayaran Midtrans. Silakan coba lagi.',
            ]);
        }

        $transactionStatus = data_get($transaction, 'transaction_status') ?? data_get($transaction, 'status');

        if (! in_array($transactionStatus, ['capture', 'settlement', 'pending', 'authorize', 'challenge'], true)) {
            throw ValidationException::withMessages([
                'jumlah' => 'Status pembayaran tidak valid: '.$transactionStatus,
            ]);
        }

        $order = DB::transaction(function () use ($request, $product, $data) {
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
            $serviceFee = max(0, round($subtotal * 0.02, 2));
            $grandTotal = $subtotal + $serviceFee;

            $order = Order::create([
                'buyer_id' => $request->user()->id,
                'product_id' => $product->id,
                'jumlah' => $data['jumlah'],
                'total_harga' => $subtotal,
                'subtotal' => $subtotal,
                'service_fee' => $serviceFee,
                'grand_total' => $grandTotal,
                'status_pengiriman' => 'dipesan',
                'payment_method' => $data['midtrans_payment_type'],
                'payment_status' => $data['midtrans_payment_status'],
                'midtrans_order_id' => $data['midtrans_order_id'],
                'midtrans_transaction_id' => $data['midtrans_transaction_id'],
            ]);

            ShipmentStatusLog::create([
                'order_id' => $order->id,
                'status' => 'dipesan',
                'catatan' => 'Pesanan berhasil dibuat oleh pembeli via Midtrans.',
                'diperbarui_pada' => now(),
            ]);

            $product->decrement('stok', $data['jumlah']);

            if ($producer = $product->producer->user ?? null) {
                $producer->notify(new NewOrderCreated([
                    'order_id' => $order->id,
                    'product_name' => $product->nama_produk,
                    'quantity' => $order->jumlah,
                    'buyer_name' => $request->user()->name,
                    'total_price' => $order->grand_total,
                    'message' => "Pesanan baru: {$order->jumlah} {$product->satuan} {$product->nama_produk} oleh {$request->user()->name}.",
                ]));
            }

            return $order;
        });

        return redirect()->route('buyer.orders.tracking', $order)
            ->with('success', 'Pesanan berhasil dibuat. Petani akan segera memproses pesanan Anda.');
    }

    /**
     * Halaman tracking pesanan untuk pembeli.
     */
    public function tracking(Request $request, Order $order)
    {
        abort_unless($order->buyer_id === $request->user()->id, 403);

        $order->load([
            'product.producer.user',
            'shipmentStatusLogs' => fn ($q) => $q->oldest(),
            'reviews',
        ]);

        return view('buyer.orders.tracking', compact('order'));
    }

    /**
     * Pembeli mengonfirmasi penerimaan barang (dikirim → diterima).
     */
    public function confirmReceived(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->buyer_id === $request->user()->id, 403);

        if ($order->status_pengiriman !== 'dikirim') {
            return back()->with('error', 'Konfirmasi penerimaan hanya bisa dilakukan saat status pesanan "Dikirim".');
        }

        $order->update(['status_pengiriman' => 'diterima']);

        ShipmentStatusLog::create([
            'order_id' => $order->id,
            'status' => 'diterima',
            'catatan' => 'Pesanan dikonfirmasi diterima oleh pembeli.',
            'diperbarui_pada' => now(),
        ]);

        return back()->with('success', 'Pesanan berhasil dikonfirmasi sebagai diterima. Terima kasih!');
    }

    /**
     * Simpan ulasan produk dari pembeli.
     */
    public function storeReview(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->buyer_id === $request->user()->id, 403, 'Anda tidak memiliki akses ke pesanan ini.');

        if ($order->status_pengiriman !== 'diterima') {
            return back()->with('error', 'Ulasan hanya dapat diberikan setelah pesanan diterima.');
        }

        if ($order->reviews()->exists()) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk pesanan ini.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'komentar' => ['nullable', 'string', 'max:1000'],
        ]);

        Review::create([
            'order_id' => $order->id,
            'buyer_id' => $request->user()->id,
            'producer_id' => $order->product->producer_id,
            'rating' => $validated['rating'],
            'komentar' => $validated['komentar'] ?? null,
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda berhasil dikirim.');
    }
}
