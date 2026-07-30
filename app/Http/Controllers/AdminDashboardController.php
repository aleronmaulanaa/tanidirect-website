<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Menampilkan Dasbor Utama Administrator.
     */
    public function index(): View
    {
        // 1. Total akun pembeli
        $totalBuyers = User::where('role', 'pembeli')->count();

        // 2. Total akun produsen (petani)
        $totalProducers = User::where('role', 'produsen')->count();

        // 3. Total pesanan di sistem
        $totalOrders = Order::count();

        // 4. Statistik Tambahan: Produk aktif & Omzet transaksi
        $totalProducts = Product::where('is_active', true)->count();
        $totalRevenue = Order::sum('grand_total') ?: Order::sum('total_harga');

        // 5. Pesanan terbaru
        $recentOrders = Order::with(['buyer', 'product'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalBuyers',
            'totalProducers',
            'totalOrders',
            'totalProducts',
            'totalRevenue',
            'recentOrders'
        ));
    }
}
