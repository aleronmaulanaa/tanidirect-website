<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Petani | TaniDirect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#F6F8F3] flex items-center justify-center px-5 py-10">

<div class="w-full max-w-4xl overflow-hidden rounded-3xl bg-white shadow-xl border border-gray-100/80 md:grid md:grid-cols-2">

    {{-- Side Banner --}}
    <div class="hidden bg-gradient-to-br from-green-800 to-emerald-600 p-10 text-white md:flex md:flex-col md:justify-between">
        <div>
            <a href="/">
                <img src="{{ asset('images/logo.png') }}" class="h-10 w-auto brightness-0 invert mb-8" alt="TaniDirect">
            </a>
            <h1 class="text-3xl font-extrabold leading-tight">
                Jual Hasil Panen <span class="text-green-200">Lebih Mudah</span>
            </h1>
            <p class="mt-4 text-sm leading-relaxed text-green-50">
                Bergabung bersama TaniDirect untuk menjual hasil panen langsung kepada pembeli dengan harga yang transparan.
            </p>
        </div>

        <div class="space-y-2 text-xs font-semibold text-green-100">
            <p>🌱 Terhubung langsung dengan pembeli</p>
            <p>📦 Kelola produk dan pesanan dengan praktis</p>
            <p>📈 Pantau harga pasar secara real-time</p>
        </div>
    </div>

    {{-- Form Section --}}
    <div class="p-8 md:p-10">

        <div class="mb-6">
            <h2 class="text-2xl font-extrabold text-gray-900">Masuk Sebagai Petani</h2>
            <p class="mt-2 text-sm text-gray-500">Kelola hasil panen dan penjualan Anda.</p>
        </div>

        @if(session('success'))
            <div class="mb-5 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('producer.login.process') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="email@contoh.com"
                    required
                    autofocus
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-600/20"
                >
                @error('email')
                    <p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="••••••••"
                    required
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-600/20"
                >
            </div>

            <button
                type="submit"
                class="mt-2 w-full rounded-xl bg-green-700 py-3.5 text-sm font-bold text-white shadow-md shadow-green-700/20 transition hover:bg-green-800 active:scale-[0.99]"
            >
                Masuk Sebagai Petani
            </button>
        </form>

        <p class="mt-6 border-t border-gray-100 pt-6 text-center text-sm text-gray-500">
            Belum memiliki akun?
            <a href="{{ route('producer.register') }}" class="font-bold text-green-700 hover:text-green-800">
                Daftar Sebagai Petani
            </a>
        </p>

    </div>

</div>

</body>
</html>