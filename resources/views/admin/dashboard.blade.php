<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | TaniDirect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f8f3] text-gray-900">

    {{-- Navbar Admin --}}
    <header class="sticky top-0 z-50 border-b border-green-100 bg-white/95 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 sm:px-8">
            <div class="flex items-center gap-3">
                <a href="{{ route('landing') }}"><img src="{{ asset('images/logo.png') }}" alt="TaniDirect" class="h-9 w-auto"></a>
                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-extrabold tracking-wider text-emerald-800 uppercase">
                    ADMINISTRATOR
                </span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-green-200 bg-green-50 px-4 py-2 text-sm font-semibold text-green-700 transition">
                    Dashboard
                </a>
                <a href="{{ route('admin.producers.index') }}" class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    Manajemen Produsen
                </a>
                <a href="{{ route('profile') }}" class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    Profil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded-xl px-4 py-2 text-sm font-semibold text-gray-600 transition hover:bg-red-50 hover:text-red-600">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-5 py-10 sm:px-8">

        {{-- Hero Banner --}}
        <section class="rounded-[2rem] bg-gradient-to-r from-green-800 via-green-700 to-emerald-600 px-7 py-10 text-white shadow-xl sm:px-10">
            <span class="rounded-full bg-white/15 px-3.5 py-1 text-xs font-bold tracking-wide">DASBOR ADMINISTRATOR</span>
            <h1 class="mt-4 text-3xl font-extrabold sm:text-4xl">Halo, {{ auth()->user()->name }} 👋</h1>
            <p class="mt-2 max-w-2xl text-green-50">Memantau aktivitas pengguna, transaksi, dan pertumbuhan ekosistem TaniDirect secara konsisten.</p>
        </section>

        {{-- Flash Messages --}}
        @if(session('error'))
            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-800 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Grid Cards Statistik Utama --}}
        <section class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">

            {{-- Card 1: Total Pembeli --}}
            <div class="rounded-3xl border border-gray-100/80 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-gray-500">Akun Pembeli</span>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-2xl">👥</div>
                </div>
                <h2 class="mt-4 text-4xl font-extrabold text-green-800">
                    {{ number_format($totalBuyers) }}
                </h2>
                <p class="mt-2 text-xs font-medium text-gray-500">Pengguna terdaftar sebagai pembeli</p>
            </div>

            {{-- Card 2: Total Produsen --}}
            <div class="rounded-3xl border border-gray-100/80 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-gray-500">Akun Produsen</span>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-2xl">🌾</div>
                </div>
                <h2 class="mt-4 text-4xl font-extrabold text-amber-700">
                    {{ number_format($totalProducers) }}
                </h2>
                <p class="mt-2 text-xs font-medium text-gray-500">Petani terdaftar di platform</p>
            </div>

            {{-- Card 3: Total Pesanan --}}
            <div class="rounded-3xl border border-gray-100/80 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-gray-500">Total Pesanan</span>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 text-2xl">📦</div>
                </div>
                <h2 class="mt-4 text-4xl font-extrabold text-blue-700">
                    {{ number_format($totalOrders) }}
                </h2>
                <p class="mt-2 text-xs font-medium text-gray-500">Transaksi pesanan di sistem</p>
            </div>

            {{-- Card 4: Total Omzet --}}
            <div class="rounded-3xl border border-gray-100/80 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-gray-500">Omzet Transaksi</span>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-100 text-2xl">💰</div>
                </div>
                <h2 class="mt-4 text-2xl font-extrabold text-purple-800 truncate" title="Rp {{ number_format($totalRevenue, 0, ',', '.') }}">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </h2>
                <p class="mt-2 text-xs font-medium text-gray-500">Akumulasi nilai transaksi</p>
            </div>

        </section>

        {{-- Tabel Transaksi Terbaru --}}
        <section class="mt-10">
            <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Transaksi Terbaru</h2>
                        <p class="text-sm text-gray-500">Pesanan paling akhir yang dibuat di platform TaniDirect.</p>
                    </div>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50/70">
                                <th class="px-6 py-4 font-semibold text-gray-600">ID Pesanan</th>
                                <th class="px-6 py-4 font-semibold text-gray-600">Pembeli</th>
                                <th class="px-6 py-4 font-semibold text-gray-600">Produk</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 text-right">Total</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 text-center">Status</th>
                                <th class="px-6 py-4 font-semibold text-gray-600">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($recentOrders as $order)
                                @php
                                    $statusColors = [
                                        'dipesan'  => 'bg-amber-100 text-amber-700',
                                        'diproses' => 'bg-blue-100 text-blue-700',
                                        'dikirim'  => 'bg-indigo-100 text-indigo-700',
                                        'diterima' => 'bg-green-100 text-green-700',
                                    ];
                                    $badgeClass = $statusColors[$order->status_pengiriman] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <tr class="transition hover:bg-green-50/30">
                                    <td class="px-6 py-4 font-bold text-gray-900">#{{ $order->id }}</td>
                                    <td class="px-6 py-4 text-gray-700 font-medium">{{ $order->buyer->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-700 font-medium">{{ $order->product->nama_produk ?? '-' }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-green-700">Rp {{ number_format($order->grand_total ?: $order->total_harga, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $badgeClass }}">
                                            {{ ucfirst($order->status_pengiriman) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                        Belum ada transaksi di dalam sistem.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

    </main>

</body>
</html>