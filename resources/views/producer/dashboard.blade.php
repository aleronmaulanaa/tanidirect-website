<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petani | TaniDirect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f8f3] text-gray-900">
    <header class="border-b border-green-100 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 sm:px-8">
            <a href="{{ route('landing') }}"><img src="{{ asset('images/logo.png') }}" alt="TaniDirect" class="h-9 w-auto"></a>
            <div class="flex items-center gap-3">
                <a href="{{ route('profile') }}" class="rounded-xl border border-green-200 px-4 py-2 text-sm font-semibold text-green-700 transition hover:bg-green-50">Profil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded-xl px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-red-50 hover:text-red-600">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-5 py-10 sm:px-8">
        <section class="rounded-[2rem] bg-gradient-to-br from-green-800 to-emerald-600 px-7 py-10 text-white shadow-xl sm:px-10">
            <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-bold tracking-wide">DASHBOARD PRODUSEN</span>
            <h1 class="mt-5 text-3xl font-extrabold sm:text-4xl">Halo, {{ auth()->user()->name }} 👋</h1>
            <p class="mt-3 max-w-2xl text-green-50">Kelola hasil panen Anda dan tampilkan produk langsung kepada pembeli TaniDirect.</p>
            <a href="{{ route('producer.products.create') }}" class="mt-7 inline-flex rounded-xl bg-white px-5 py-3 text-sm font-bold text-green-700 transition hover:bg-green-50">+ Tambah Produk</a>
        </section>

        <section class="mt-8 grid gap-5 md:grid-cols-3">
            <a href="{{ route('producer.products.index') }}" class="rounded-3xl bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-green-100 text-xl">🌾</div>
                <h2 class="mt-5 text-lg font-bold">Produk Saya</h2>
                <p class="mt-2 text-sm leading-6 text-gray-500">Tambah, ubah, aktifkan, atau nonaktifkan hasil panen Anda.</p>
                <span class="mt-5 inline-block text-sm font-bold text-green-700">Kelola produk →</span>
            </a>
            <div class="rounded-3xl bg-white p-6 opacity-75 shadow-sm">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-100 text-xl">📦</div>
                <h2 class="mt-5 text-lg font-bold">Pesanan</h2>
                <p class="mt-2 text-sm leading-6 text-gray-500">Pantau pesanan pembeli dan status pengiriman pada tahap berikutnya.</p>
            </div>
            <a href="{{ route('price-tracker') }}" class="rounded-3xl bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-100 text-xl">📈</div>
                <h2 class="mt-5 text-lg font-bold">Harga Referensi</h2>
                <p class="mt-2 text-sm leading-6 text-gray-500">Pantau perbandingan harga produsen dan konsumen Jawa Timur.</p>
                <span class="mt-5 inline-block text-sm font-bold text-green-700">Lihat harga →</span>
            </a>
        </section>
    </main>
</body>
</html>
