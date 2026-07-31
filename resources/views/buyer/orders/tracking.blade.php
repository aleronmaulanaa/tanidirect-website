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

    <main class="mx-auto max-w-5xl px-6 py-10">

        {{-- Header Page --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Lacak Pesanan <span class="text-green-700">#{{ $order->id }}</span></h1>
                <p class="mt-2 text-gray-500">Pantau status pengiriman pesanan Anda.</p>
            </div>
            <a href="{{ route('buyer.dashboard') }}#pesanan"
               class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                ← Kembali ke Pesanan
            </a>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mt-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-800 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-800 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

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

        <div class="mt-8 rounded-3xl bg-white p-6 shadow-sm sm:p-8">
            <div class="flex items-center justify-between">
                @foreach($allStatuses as $idx => $s)
                    <div class="flex flex-col items-center gap-2">
                        <div class="flex h-12 w-12 sm:h-14 sm:w-14 items-center justify-center rounded-full text-xl sm:text-2xl transition
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

        {{-- Grid Utama: Detail Pesanan & Riwayat Status --}}
        <div class="mt-8 grid gap-8 lg:grid-cols-5">

            {{-- Kolom Kiri: Info Pesanan --}}
            <div class="lg:col-span-2">
                <div class="rounded-3xl bg-white p-6 shadow-sm border border-gray-100/80">
                    <h2 class="text-lg font-bold text-gray-900">Detail Pesanan</h2>
                    <div class="mt-5 space-y-4 text-sm">
                        <div class="flex justify-between border-b border-gray-100 pb-3">
                            <span class="text-gray-500">Produk</span>
                            <span class="font-semibold text-gray-900 text-right">{{ $order->product->nama_produk }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-3">
                            <span class="text-gray-500">Produsen</span>
                            <span class="font-semibold text-gray-900 text-right">{{ $order->product->producer->user->name ?? '-' }}</span>
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

            {{-- Kolom Kanan: Timeline Riwayat Status --}}
            <div class="lg:col-span-3">
                <div class="rounded-3xl bg-white p-6 shadow-sm border border-gray-100/80">
                    <h2 class="text-lg font-bold text-gray-900">Riwayat Pengiriman</h2>
                    <p class="mt-1 text-sm text-gray-500">Setiap perubahan status tercatat di sini.</p>

                    <div class="mt-6 space-y-6">
                        @forelse($order->shipmentStatusLogs as $log)
                            @php
                                $logColors = [
                                    'dipesan'  => 'border-amber-400 bg-amber-50/70',
                                    'diproses' => 'border-blue-400 bg-blue-50/70',
                                    'dikirim'  => 'border-indigo-400 bg-indigo-50/70',
                                    'diterima' => 'border-green-400 bg-green-50/70',
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

                            <div class="relative flex gap-4">
                                {{-- Line Connector --}}
                                @if(! $loop->last)
                                    <div class="absolute left-[13px] top-7 bottom-0 w-0.5 bg-gray-200"></div>
                                @endif

                                {{-- Dot --}}
                                <div class="relative z-10 mt-1 flex h-7 w-7 shrink-0 items-center justify-center">
                                    <div class="h-3.5 w-3.5 rounded-full {{ $dotColor }} ring-4 ring-white
                                        {{ $isLatest ? 'animate-pulse' : '' }}"></div>
                                </div>

                                {{-- Content Card --}}
                                <div class="flex-1 rounded-2xl border-l-4 {{ $logBg }} p-4 shadow-2xs {{ $isLatest ? 'ring-2 ring-green-500/20' : '' }}">
                                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                        <h3 class="font-bold text-gray-900">
                                            {{ $statusIcons[$log->status] ?? '📦' }} {{ ucfirst($log->status) }}
                                            @if($isLatest)
                                                <span class="ml-2 inline-block rounded-full bg-green-600 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-white">Terkini</span>
                                            @endif
                                        </h3>
                                        <time class="text-xs font-medium text-gray-500">
                                            {{ $log->diperbarui_pada?->format('d M Y, H:i') ?? $log->created_at->format('d M Y, H:i') }}
                                        </time>
                                    </div>
                                    @if($log->catatan)
                                        <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ $log->catatan }}</p>
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

        {{-- Tombol Konfirmasi Penerimaan --}}
        @if($order->status_pengiriman === 'dikirim')
        <div class="mt-8 rounded-3xl border-2 border-green-500/20 bg-white p-6 shadow-sm sm:p-8">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-green-100 text-xl shadow-2xs">📦</span>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Konfirmasi Penerimaan Barang</h2>
                    <p class="text-sm text-gray-500">Pesanan Anda sudah dikirim. Silakan konfirmasi jika barang sudah diterima.</p>
                </div>
            </div>
            <form action="{{ route('buyer.orders.confirmReceived', $order) }}" method="POST" class="mt-5">
                @csrf
                @method('PATCH')
                <button
                    type="submit"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-green-700 px-6 py-3.5 text-sm font-bold text-white transition hover:bg-green-800 active:scale-[0.99] sm:w-auto"
                    onclick="return confirm('Apakah Anda yakin barang sudah diterima dengan baik?')"
                >
                    ✅ Barang Sudah Diterima
                </button>
            </form>
        </div>
        @endif

        {{-- Section Ulasan Produk --}}
        @php
            $userReview = $order->reviews->first();
        @endphp

        <div class="mt-8">
            @if($order->status_pengiriman === 'diterima')
                @if($userReview)
                    {{-- Riwayat Ulasan (Sudah Diulas) --}}
                    <div class="rounded-3xl border border-green-200 bg-white p-6 shadow-sm sm:p-8">
                        <div class="flex flex-col gap-3 border-b border-gray-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-center gap-3">
                                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-100 text-xl shadow-2xs">⭐</span>
                                <div>
                                    <h2 class="text-lg font-bold text-gray-900">Ulasan Anda</h2>
                                    <p class="text-xs text-gray-500">Dikirim pada {{ $userReview->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 rounded-full bg-amber-50 px-4 py-1.5 text-sm font-extrabold text-amber-700 ring-1 ring-amber-200/70">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $userReview->rating)
                                        <svg class="h-4 w-4 fill-amber-400 text-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @else
                                        <svg class="h-4 w-4 fill-gray-200 text-gray-200" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endif
                                @endfor
                                <span class="ml-1.5 text-xs font-bold text-gray-700">({{ $userReview->rating }}/5)</span>
                            </div>
                        </div>
                        @if($userReview->komentar)
                            <div class="mt-4 rounded-2xl border border-gray-100 bg-gray-50/80 p-4">
                                <p class="text-sm italic leading-relaxed text-gray-700">"{{ $userReview->komentar }}"</p>
                            </div>
                        @else
                            <p class="mt-3 text-xs italic text-gray-400">Tidak ada ulasan tertulis.</p>
                        @endif
                    </div>
                @else
                    {{-- Form Ulasan (Belum Diulas) --}}
                    <div class="rounded-3xl border-2 border-green-500/20 bg-white p-6 shadow-sm sm:p-8">
                        <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                            <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-green-100 text-xl shadow-2xs">✍️</span>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Beri Ulasan Produk</h2>
                                <p class="text-sm text-gray-500">Bagikan pengalaman belanja Anda untuk membantu pembeli lain dan petani.</p>
                            </div>
                        </div>

                        <form action="{{ route('buyer.orders.review', $order) }}" method="POST" class="mt-6 space-y-6">
                            @csrf

                            {{-- Star Rating Picker (Alpine.js Interactive) --}}
                            <div x-data="{ rating: {{ old('rating', 5) }}, hover: 0 }" class="space-y-2">
                                <input type="hidden" name="rating" :value="rating" required>

                                <label class="block text-sm font-bold text-gray-800">
                                    Rating Kualitas Produk <span class="text-red-500">*</span>
                                </label>

                                <div class="flex flex-wrap items-center gap-3">
                                    <div class="flex items-center gap-1" @mouseleave="hover = 0">
                                        <template x-for="star in 5" :key="star">
                                            <button type="button"
                                                @click="rating = star"
                                                @mouseenter="hover = star"
                                                class="p-1 transition-transform duration-150 transform hover:scale-125 focus:outline-none"
                                                :title="star + ' Bintang'">
                                                <svg class="h-8 w-8 transition-colors duration-150 fill-current"
                                                    :class="(hover ? star <= hover : star <= rating) ? 'text-amber-400 fill-amber-400 drop-shadow-xs' : 'text-gray-300 fill-gray-200'"
                                                    viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </button>
                                        </template>
                                    </div>

                                    <span class="rounded-full bg-amber-50 px-3.5 py-1 text-xs font-bold text-amber-700 ring-1 ring-amber-200/70"
                                        x-text="hover ? hover + ' Bintang' : (rating ? rating + ' dari 5 Bintang' : 'Pilih Rating')">
                                    </span>
                                </div>

                                @error('rating')
                                    <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Komentar --}}
                            <div>
                                <label for="komentar" class="block text-sm font-bold text-gray-800">
                                    Komentar Ulasan <span class="text-xs font-normal text-gray-400">(opsional)</span>
                                </label>
                                <textarea
                                    id="komentar"
                                    name="komentar"
                                    rows="3"
                                    placeholder="Tulis ulasan Anda mengenai kualitas barang, pengemasan, atau pelayanan petani..."
                                    class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm transition focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-600/20"
                                >{{ old('komentar') }}</textarea>
                                @error('komentar')
                                    <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-green-700 px-6 py-3.5 text-sm font-bold text-white transition hover:bg-green-800 active:scale-[0.99] sm:w-auto"
                            >
                                🌟 Kirim Ulasan
                            </button>
                        </form>
                    </div>
                @endif
            @else
                <div class="rounded-3xl border border-dashed border-gray-200 bg-white/60 p-6 text-center text-sm text-gray-500 shadow-2xs">
                    💡 Form ulasan akan terbuka secara otomatis setelah status pesanan Anda <strong class="text-green-700">Diterima</strong>.
                </div>
            @endif
        </div>

    </main>

</body>
</html>
