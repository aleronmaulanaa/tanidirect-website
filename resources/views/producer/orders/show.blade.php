<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #{{ $order->id }} | TaniDirect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f8f3] text-gray-900">

    {{-- Navbar --}}
    <header class="border-b border-green-100 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 sm:px-8">
            <a href="{{ route('landing') }}"><img src="{{ asset('images/logo.png') }}" alt="TaniDirect" class="h-9 w-auto"></a>
            <div class="flex items-center gap-3">
                <a href="{{ route('producer.orders.index') }}" class="rounded-xl border border-green-200 px-4 py-2 text-sm font-semibold text-green-700 transition hover:bg-green-50">Pesanan</a>
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
                <h1 class="text-3xl font-extrabold text-gray-900">Detail Pesanan <span class="text-green-700">#{{ $order->id }}</span></h1>
                <p class="mt-2 text-gray-500">Kelola status pengiriman pesanan ini.</p>
            </div>
            <a href="{{ route('producer.orders.index') }}"
               class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                ← Kembali
            </a>
        </div>

        @if(session('success'))
            <div class="mt-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-800">
                {{ session('error') }}
            </div>
        @endif

        <div class="mt-8 grid gap-8 lg:grid-cols-5">

            {{-- Kolom Kiri: Info Pesanan + Update Status --}}
            <div class="space-y-8 lg:col-span-2">

                {{-- Info Pesanan --}}
                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900">Informasi Pesanan</h2>
                    <div class="mt-5 space-y-4 text-sm">
                        <div class="flex justify-between border-b border-gray-100 pb-3">
                            <span class="text-gray-500">Produk</span>
                            <span class="font-semibold text-gray-900">{{ $order->product->nama_produk }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-3">
                            <span class="text-gray-500">Pembeli</span>
                            <span class="font-semibold text-gray-900">{{ $order->buyer->name }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-3">
                            <span class="text-gray-500">Jumlah</span>
                            <span class="font-semibold text-gray-900">{{ $order->jumlah }} {{ $order->product->satuan }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-3">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="font-semibold">Rp {{ number_format($order->subtotal ?: $order->total_harga, 0, ',', '.') }}</span>
                        </div>
                        @if($order->service_fee > 0)
                        <div class="flex justify-between border-b border-gray-100 pb-3">
                            <span class="text-gray-500">Biaya Layanan</span>
                            <span class="font-semibold">Rp {{ number_format($order->service_fee, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between border-b border-gray-100 pb-3">
                            <span class="text-gray-500">Grand Total</span>
                            <span class="font-bold text-green-700">Rp {{ number_format($order->grand_total ?: $order->total_harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-3">
                            <span class="text-gray-500">Tanggal Pesan</span>
                            <span class="font-semibold">{{ $order->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Status Saat Ini</span>
                            @php
                                $statusColors = [
                                    'dipesan'  => 'bg-amber-100 text-amber-700',
                                    'diproses' => 'bg-blue-100 text-blue-700',
                                    'dikirim'  => 'bg-indigo-100 text-indigo-700',
                                    'diterima' => 'bg-green-100 text-green-700',
                                ];
                                $badgeClass = $statusColors[$order->status_pengiriman] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $badgeClass }}">
                                {{ ucfirst($order->status_pengiriman) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Update Status --}}
                @if($nextStatus)
                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900">Perbarui Status</h2>
                    <p class="mt-2 text-sm text-gray-500">
                        Ubah status dari
                        <span class="font-bold text-gray-700">{{ ucfirst($order->status_pengiriman) }}</span>
                        menjadi
                        <span class="font-bold text-green-700">{{ ucfirst($nextStatus) }}</span>
                    </p>

                    <form method="POST" action="{{ route('producer.orders.updateStatus', $order) }}" class="mt-5">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label for="catatan" class="block text-sm font-semibold text-gray-700">
                                Catatan
                                @if($nextStatus === 'dikirim')
                                    <span class="font-normal text-gray-400">(opsional — bisa isi nomor resi)</span>
                                @else
                                    <span class="font-normal text-gray-400">(opsional)</span>
                                @endif
                            </label>
                            <textarea
                                id="catatan"
                                name="catatan"
                                rows="3"
                                placeholder="{{ $nextStatus === 'dikirim' ? 'Contoh: Dikirim via JNE, resi JT12345678' : 'Tambahkan catatan...' }}"
                                class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm transition focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20"
                            ></textarea>
                        </div>

                        @php
                            $btnColors = [
                                'diproses' => 'bg-blue-600 hover:bg-blue-700',
                                'dikirim'  => 'bg-indigo-600 hover:bg-indigo-700',
                                'diterima' => 'bg-green-600 hover:bg-green-700',
                            ];
                            $btnClass = $btnColors[$nextStatus] ?? 'bg-green-700 hover:bg-green-800';

                            $btnIcons = [
                                'diproses' => '⚙️',
                                'dikirim'  => '🚚',
                                'diterima' => '✅',
                            ];
                            $icon = $btnIcons[$nextStatus] ?? '→';
                        @endphp

                        <button
                            type="submit"
                            class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-xl {{ $btnClass }} px-6 py-3 text-sm font-bold text-white transition"
                            onclick="return confirm('Yakin ingin mengubah status menjadi {{ ucfirst($nextStatus) }}?')"
                        >
                            <span>{{ $icon }}</span>
                            Tandai Sebagai {{ ucfirst($nextStatus) }}
                        </button>
                    </form>
                </div>
                @else
                <div class="rounded-3xl border-2 border-dashed border-green-200 bg-green-50/50 p-6 text-center">
                    <div class="text-4xl">✅</div>
                    <h3 class="mt-3 text-lg font-bold text-green-800">Pesanan Selesai</h3>
                    <p class="mt-2 text-sm text-green-600">Pesanan ini sudah diterima oleh pembeli. Tidak ada aksi lanjutan yang diperlukan.</p>
                </div>
                @endif

            </div>

            {{-- Kolom Kanan: Timeline Riwayat Status --}}
            <div class="lg:col-span-3">
                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900">Riwayat Status Pengiriman</h2>
                    <p class="mt-2 text-sm text-gray-500">Timeline perjalanan pesanan ini.</p>

                    {{-- Progress Bar Horizontal --}}
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

                    <div class="mt-6 flex items-center justify-between">
                        @foreach($allStatuses as $idx => $s)
                            <div class="flex flex-col items-center gap-2">
                                <div class="flex h-12 w-12 items-center justify-center rounded-full text-xl transition
                                    {{ $idx <= $currentIndex ? 'bg-green-100 ring-2 ring-green-500' : 'bg-gray-100' }}">
                                    {{ $statusIcons[$s] }}
                                </div>
                                <span class="text-xs font-bold {{ $idx <= $currentIndex ? 'text-green-700' : 'text-gray-400' }}">
                                    {{ $statusLabels[$s] }}
                                </span>
                            </div>
                            @if(! $loop->last)
                                <div class="mx-1 h-1 flex-1 rounded-full {{ $idx < $currentIndex ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                            @endif
                        @endforeach
                    </div>

                    {{-- Timeline Vertikal Detail --}}
                    <div class="mt-10 space-y-0">
                        @foreach($order->shipmentStatusLogs as $log)
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
                            @endphp

                            <div class="relative flex gap-4 pb-8 last:pb-0">
                                {{-- Garis Vertikal --}}
                                @if(! $loop->last)
                                    <div class="absolute left-[11px] top-6 h-full w-0.5 bg-gray-200"></div>
                                @endif

                                {{-- Dot --}}
                                <div class="relative z-10 mt-1 flex h-6 w-6 shrink-0 items-center justify-center">
                                    <div class="h-3 w-3 rounded-full {{ $dotColor }} ring-4 ring-white"></div>
                                </div>

                                {{-- Konten --}}
                                <div class="flex-1 rounded-2xl border-l-4 {{ $logBg }} p-4">
                                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                        <h4 class="font-bold text-gray-900">
                                            {{ $statusIcons[$log->status] ?? '📦' }} {{ ucfirst($log->status) }}
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
                        @endforeach
                    </div>

                </div>
            </div>

        </div>

        {{-- Card Ulasan Pembeli --}}
        @php
            $buyerReview = $order->reviews->first();
        @endphp

        <div class="mt-8 rounded-3xl bg-white p-6 shadow-sm border border-gray-100/80">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-100 text-xl shadow-2xs">⭐</span>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Ulasan Pembeli</h2>
                    <p class="text-xs text-gray-500">Penilaian kualitas produk dan pengalaman belanja dari pembeli.</p>
                </div>
            </div>

            <div class="mt-5">
                @if($buyerReview)
                    <div class="rounded-2xl border border-green-200 bg-green-50/50 p-5">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-b border-green-100 pb-3">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-gray-900">{{ $order->buyer->name }}</span>
                                <span class="text-xs text-gray-400">•</span>
                                <time class="text-xs text-gray-500">{{ $buyerReview->created_at->format('d M Y, H:i') }}</time>
                            </div>
                            <div class="flex items-center gap-1 rounded-full bg-amber-50 px-3.5 py-1 text-sm font-extrabold text-amber-700 ring-1 ring-amber-200/70">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $buyerReview->rating)
                                        <svg class="h-4 w-4 fill-amber-400 text-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @else
                                        <svg class="h-4 w-4 fill-gray-200 text-gray-200" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endif
                                @endfor
                                <span class="ml-1.5 text-xs font-bold text-gray-700">({{ $buyerReview->rating }}/5)</span>
                            </div>
                        </div>

                        @if($buyerReview->komentar)
                            <p class="mt-3 text-sm italic leading-relaxed text-gray-700 bg-white/80 p-3.5 rounded-xl border border-green-100">
                                "{{ $buyerReview->komentar }}"
                            </p>
                        @else
                            <p class="mt-3 text-xs italic text-gray-400">Pembeli memberikan rating bintang tanpa ulasan tertulis.</p>
                        @endif
                    </div>
                @else
                    <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50/50 p-6 text-center">
                        <p class="text-sm font-medium text-gray-400">Pembeli belum memberikan ulasan</p>
                    </div>
                @endif
            </div>
        </div>

    </main>

</body>
</html>
