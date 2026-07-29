<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ShipmentStatusLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProducerOrderController extends Controller
{
    /**
     * Alur status pengiriman yang diizinkan.
     * Key = status saat ini, Value = status berikutnya.
     */
    private const STATUS_FLOW = [
        'dipesan'  => 'diproses',
        'diproses' => 'dikirim',
        'dikirim'  => 'diterima',
    ];

    /**
     * Daftar semua pesanan untuk produk milik produsen yang sedang login.
     */
    public function index(Request $request): View
    {
        $profile = $request->user()->producerProfile;

        abort_unless($profile, 403, 'Profil produsen belum tersedia.');

        $orders = Order::with(['product', 'buyer'])
            ->whereHas('product', fn ($q) => $q->where('producer_id', $profile->id))
            ->latest()
            ->paginate(10);

        return view('producer.orders.index', compact('orders'));
    }

    /**
     * Detail satu pesanan beserta timeline riwayat status.
     */
    public function show(Request $request, Order $order): View
    {
        $this->ensureOwnership($request, $order);

        $order->load([
            'product',
            'buyer',
            'shipmentStatusLogs' => fn ($q) => $q->oldest(),
            'reviews',
        ]);

        $nextStatus = self::STATUS_FLOW[$order->status_pengiriman] ?? null;

        return view('producer.orders.show', compact('order', 'nextStatus'));
    }

    /**
     * Perbarui status pengiriman pesanan.
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $this->ensureOwnership($request, $order);

        $currentStatus = $order->status_pengiriman;
        $nextStatus = self::STATUS_FLOW[$currentStatus] ?? null;

        if (! $nextStatus) {
            return back()->with('error', 'Status pesanan ini sudah final dan tidak bisa diperbarui lagi.');
        }

        $data = $request->validate([
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $order->update(['status_pengiriman' => $nextStatus]);

        ShipmentStatusLog::create([
            'order_id'        => $order->id,
            'status'          => $nextStatus,
            'catatan'         => $data['catatan'] ?? $this->defaultNote($nextStatus),
            'diperbarui_pada' => now(),
        ]);

        return back()->with('success', 'Status pesanan berhasil diperbarui menjadi "' . ucfirst($nextStatus) . '".');
    }

    /**
     * Pastikan pesanan ini milik produk dari produsen yang sedang login.
     */
    private function ensureOwnership(Request $request, Order $order): void
    {
        $profile = $request->user()->producerProfile;

        abort_unless($profile, 403);
        abort_unless($order->product && $order->product->producer_id === $profile->id, 403);
    }

    /**
     * Catatan default untuk setiap perubahan status.
     */
    private function defaultNote(string $status): string
    {
        return match ($status) {
            'diproses' => 'Pesanan sedang diproses oleh produsen.',
            'dikirim'  => 'Pesanan telah dikirim.',
            'diterima' => 'Pesanan telah diterima oleh pembeli.',
            default    => 'Status diperbarui.',
        };
    }
}
