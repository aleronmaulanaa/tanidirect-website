<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Masuk | TaniDirect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f8f3] text-gray-900">

    {{-- Navbar --}}
    <header class="border-b border-green-100 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 sm:px-8">
            <a href="{{ route('landing') }}"><img src="{{ asset('images/logo.png') }}" alt="TaniDirect" class="h-9 w-auto"></a>
            <div class="flex items-center gap-3">
                <a href="{{ route('producer.dashboard') }}" class="rounded-xl border border-green-200 px-4 py-2 text-sm font-semibold text-green-700 transition hover:bg-green-50">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded-xl px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-red-50 hover:text-red-600">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-5 py-10 sm:px-8">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Pesanan Masuk</h1>
                <p class="mt-2 text-gray-500">Kelola pesanan dari pembeli untuk produk Anda.</p>
            </div>
            <a href="{{ route('producer.dashboard') }}"
               class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                ← Kembali
            </a>
        </div>

        @if(session('success'))
            <div class="mt-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tabel Pesanan --}}
        <div class="mt-8 overflow-hidden rounded-3xl bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/50">
                            <th class="px-6 py-4 font-semibold text-gray-600">Produk</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Pembeli</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-center">Jumlah</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-right">Total</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-center">Status</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Tanggal</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($orders as $order)
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
                                <td class="px-6 py-4 font-semibold text-gray-900">{{ $order->product->nama_produk }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $order->buyer->name }}</td>
                                <td class="px-6 py-4 text-center">{{ $order->jumlah }} {{ $order->product->satuan }}</td>
                                <td class="px-6 py-4 text-right font-bold text-green-700">Rp {{ number_format($order->grand_total ?: $order->total_harga, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $badgeClass }}">
                                        {{ ucfirst($order->status_pengiriman) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('producer.orders.show', $order) }}"
                                       class="inline-flex rounded-xl bg-green-700 px-4 py-2 text-xs font-bold text-white transition hover:bg-green-800">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    Belum ada pesanan masuk untuk produk Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($orders->hasPages())
                <div class="border-t border-gray-100 px-6 py-4">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>

    </main>

</body>
</html>
