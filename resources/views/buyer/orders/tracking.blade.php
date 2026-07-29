<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Pesanan #{{ $order->id }} | TaniDirect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f8f3] text-gray-900">

    {{-- Navbar --}}
    <header class="sticky top-0 z-50 border-b border-gray-100 bg-white/90 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
            <a href="{{ route('landing') }}"><img src="{{ asset('images/logo.png') }}" alt="TaniDirect" class="h-10"></a>
            <div class="flex items-center gap-3">
                <a href="{{ route('buyer.dashboard') }}" class="rounded-xl border border-green-200 px-4 py-2 text-sm font-semibold text-green-700 transition hover:bg-green-50">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded-xl bg-green-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-green-800">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-4xl px-6 py-10">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Lacak Pesanan <span class="text-green-700">#{{ $order->id }}</span></h1>
                <p class="mt-2 text-gray-500">Pantau status pengiriman pesanan Anda.</p>
            </div>
            <a href="{{ route('buyer.dashboard') }}#pesanan"
               class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                ← Kembali
            </a>
        </div>

        {{-- Progress Bar --}}
        @php
            $allStatuses = ['dipesan', 'diproses', 'dikirim', 'diterima'];
            $currentIndex = array_search($order->status_pengiriman, $allStatuses);

            $statusIcons = [
                'dipesan'  => '📋',
                'diproses' => '⚙️',
                'dikirim'  => '🚚',
                'diterima' => '✅',
            ];

            $statusLabels = [
                'dipesan'  => 'Dipesan',
                'diproses' => 'Diproses',
                'dikirim'  => 'Dikirim',
                'diterima' => 'Diterima',
            ];
        @endphp

        <div class="mt-8 rounded-3xl bg-white p-6 shadow-sm sm:p-8">
            <div class="flex items-center justify-between">
                @foreach($allStatuses as $idx => $s)
                    <div class="flex flex-col items-center gap-2">
                        <div class="flex h-14 w-14 items-center justify-center rounded-full text-2xl transition
                            {{ $idx <= $currentIndex ? 'bg-green-100 ring-4 ring-green-400/30' : 'bg-gray-100' }}
                            {{ $idx === $currentIndex ? 'scale-110 ring-green-500/50 shadow-lg shadow-green-200' : '' }}">
                            {{ $statusIcons[$s] }}
                        </div>
                        <span class="text-xs font-bold {{ $idx <= $currentIndex ? 'text-green-700' : 'text-gray-400' }}">
                            {{ $statusLabels[$s] }}
                        </span>
                    </div>
                    @if(! $loop->last)
                        <div class="mx-2 h-1.5 flex-1 rounded-full overflow-hidden bg-gray-200">
                            <div class="h-full rounded-full bg-gradient-to-r from-green-500 to-emerald-500 transition-all duration-500
                                {{ $idx < $currentIndex ? 'w-full' : 'w-0' }}"></div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-5">

            {{-- Info Pesanan --}}
            <div class="lg:col-span-2">
                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900">Detail Pesanan</h2>
                    <div class="mt-5 space-y-4 text-sm">
                        <div class="flex justify-between border-b border-gray-100 pb-3">
                            <span class="text-gray-500">Produk</span>
                            <span class="font-semibold text-gray-900">{{ $order->product->nama_produk }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-3">
                            <span class="text-gray-500">Produsen</span>
                            <span class="font-semibold text-gray-900">{{ $order->product->producer->user->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-3">
                            <span class="text-gray-500">Jumlah</span>
                            <span class="font-semibold">{{ $order->jumlah }} {{ $order->product->satuan }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-3">
                            <span class="text-gray-500">Total Bayar</span>
                            <span class="font-bold text-green-700">Rp {{ number_format($order->grand_total ?: $order->total_harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-3">
                            <span class="text-gray-500">Tanggal Pesan</span>
                            <span class="font-semibold">{{ $order->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Metode Bayar</span>
                            <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $order->payment_method ?? '-')) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Timeline Riwayat --}}
            <div class="lg:col-span-3">
                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900">Riwayat Pengiriman</h2>
                    <p class="mt-2 text-sm text-gray-500">Setiap perubahan status tercatat di sini.</p>

                    <div class="mt-8 space-y-0">
                        @forelse($order->shipmentStatusLogs as $log)
                            @php
                                $logColors = [
                                    'dipesan'  => 'border-amber-400 bg-amber-50',
                                    'diproses' => 'border-blue-400 bg-blue-50',
                                    'dikirim'  => 'border-indigo-400 bg-indigo-50',
                                    'diterima' => 'border-green-400 bg-green-50',
                                ];
                                $logDotColors = [
                                    'dipesan'  => 'bg-amber-500',
                                    'diproses' => 'bg-blue-500',
                                    'dikirim'  => 'bg-indigo-500',
                                    'diterima' => 'bg-green-500',
                                ];
                                $logBg = $logColors[$log->status] ?? 'border-gray-300 bg-gray-50';
                                $dotColor = $logDotColors[$log->status] ?? 'bg-gray-400';
                                $isLatest = $loop->last;
                            @endphp

                            <div class="relative flex gap-4 pb-8 last:pb-0">
                                {{-- Garis Vertikal --}}
                                @if(! $loop->last)
                                    <div class="absolute left-[13px] top-7 h-full w-0.5 bg-gray-200"></div>
                                @endif

                                {{-- Dot --}}
                                <div class="relative z-10 mt-1 flex h-7 w-7 shrink-0 items-center justify-center">
                                    <div class="h-3.5 w-3.5 rounded-full {{ $dotColor }} ring-4 ring-white
                                        {{ $isLatest ? 'animate-pulse' : '' }}"></div>
                                </div>

                                {{-- Konten --}}
                                <div class="flex-1 rounded-2xl border-l-4 {{ $logBg }} p-4 {{ $isLatest ? 'ring-2 ring-green-200' : '' }}">
                                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                        <h4 class="font-bold text-gray-900">
                                            {{ $statusIcons[$log->status] ?? '📦' }} {{ ucfirst($log->status) }}
                                            @if($isLatest)
                                                <span class="ml-2 rounded-full bg-green-600 px-2 py-0.5 text-[10px] font-bold uppercase text-white">Terkini</span>
                                            @endif
                                        </h4>
                                        <time class="text-xs text-gray-500">
                                            {{ $log->diperbarui_pada?->format('d M Y, H:i') ?? $log->created_at->format('d M Y, H:i') }}
                                        </time>
                                    </div>
                                    @if($log->catatan)
                                        <p class="mt-2 text-sm text-gray-600">{{ $log->catatan }}</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-sm text-gray-500">
                                Belum ada riwayat status untuk pesanan ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

    </main>

</body>
</html>
