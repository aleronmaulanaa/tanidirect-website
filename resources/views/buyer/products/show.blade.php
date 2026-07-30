<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->nama_produk }} | TaniDirect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#F8FAF5] text-gray-900">
    <header class="sticky top-0 z-50 border-b border-gray-100 bg-white/95 shadow-sm backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 py-4 sm:px-8">
            <a href="{{ route('buyer.dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="TaniDirect" class="h-9 w-auto">
                <span class="hidden text-sm font-semibold text-gray-600 sm:inline">Belanja Hasil Panen</span>
            </a>
            <a href="{{ route('buyer.dashboard') }}" class="rounded-xl px-4 py-2 text-sm font-semibold text-green-700 hover:bg-green-50">← Kembali</a>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-5 py-10 sm:px-8">
        <div class="mb-6 text-sm font-medium text-gray-500">
            <a href="{{ route('buyer.dashboard') }}" class="hover:text-green-700">Dashboard Pembeli</a> <span class="mx-2">/</span> {{ $product->nama_produk }}
        </div>

        <section class="grid overflow-hidden rounded-[2rem] bg-white shadow-sm lg:grid-cols-2">
            <div class="min-h-[320px] bg-green-50 lg:min-h-[540px]">
                @if($product->gambar)
                    <img src="{{ asset('storage/'.$product->gambar) }}" alt="{{ $product->nama_produk }}" class="h-full w-full object-cover">
                @else
                    <div class="flex h-full min-h-[320px] items-center justify-center">
                        <img src="{{ asset('images/logo.png') }}" alt="TaniDirect" class="h-28 w-auto opacity-70">
                    </div>
                @endif
            </div>

            <div class="p-7 sm:p-10">
                <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">LANGSUNG DARI PETANI</span>
                <h1 class="mt-4 text-3xl font-extrabold tracking-tight sm:text-4xl">{{ $product->nama_produk }}</h1>
                <p class="mt-2 text-sm text-gray-500">{{ $product->producer->user->name ?? 'Petani TaniDirect' }} · {{ $product->producer->kabupaten_kota ?? '-' }}</p>

                {{-- Rating & Total Ulasan --}}
                @php
                    $avgRating = $product->average_rating;
                    $totalReviews = $product->total_reviews;
                @endphp
                <div class="mt-3 flex items-center gap-2">
                    @if($totalReviews > 0)
                        <div class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3.5 py-1 text-xs font-extrabold text-amber-700 ring-1 ring-amber-200">
                            <span class="text-amber-500 text-sm">⭐</span>
                            <span>{{ number_format($avgRating, 1) }}</span>
                            <span class="font-normal text-gray-500">({{ $totalReviews }} Ulasan)</span>
                        </div>
                    @else
                        <div class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3.5 py-1 text-xs font-medium text-gray-500">
                            <span>⭐ Belum ada ulasan</span>
                        </div>
                    @endif
                </div>

                <div class="mt-7 rounded-2xl bg-[#F3FAF2] p-5">
                    <p class="text-sm font-medium text-gray-500">Harga petani</p>
                    <p class="mt-1 text-3xl font-extrabold text-green-700">Rp {{ number_format($product->harga_jual, 0, ',', '.') }} <span class="text-base font-medium text-gray-500">/{{ $product->satuan }}</span></p>
                    <p class="mt-3 text-sm text-gray-600">Stok tersedia: <strong class="text-gray-900">{{ number_format($product->stok) }} {{ $product->satuan }}</strong></p>
                </div>

                @if($product->deskripsi)
                    <div class="mt-7">
                        <h2 class="font-bold text-gray-900">Tentang produk</h2>
                        <p class="mt-2 whitespace-pre-line leading-7 text-gray-600">{{ $product->deskripsi }}</p>
                    </div>
                @endif

                <form action="{{ route('buyer.orders.checkout', $product) }}" method="POST" class="mt-8 border-t border-gray-100 pt-7">
                    @csrf
                    @if($errors->any())
                        <div class="mb-5 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
                    @endif
                    <label for="jumlah" class="block text-sm font-bold text-gray-700">Jumlah pesanan (kg)</label>
                    <div class="mt-2 flex flex-col gap-3 sm:flex-row">
                        <input id="jumlah" name="jumlah" type="number" min="1" max="{{ $product->stok }}" value="{{ old('jumlah', 1) }}" required
                            class="w-full rounded-xl border-gray-300 px-4 py-3 text-lg font-semibold focus:border-green-600 focus:ring-green-600 sm:max-w-40" {{ $product->stok === 0 ? 'disabled' : '' }}>
                        <button type="submit" class="rounded-xl bg-green-700 px-6 py-3 font-bold text-white transition hover:bg-green-800 disabled:cursor-not-allowed disabled:bg-gray-300" {{ $product->stok === 0 ? 'disabled' : '' }}>
                            {{ $product->stok === 0 ? 'Stok Habis' : 'Pesan Sekarang' }}
                        </button>
                    </div>
                    <p class="mt-3 text-xs leading-5 text-gray-500">Anda akan diarahkan ke halaman pembayaran simulasi sebelum pesanan masuk ke daftar Pesanan Saya.</p>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
