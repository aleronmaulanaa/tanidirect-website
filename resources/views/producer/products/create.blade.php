<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk | TaniDirect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f8f3] text-gray-900">
    <main class="mx-auto max-w-3xl px-5 py-10 sm:px-8">
        <a href="{{ route('producer.products.index') }}" class="text-sm font-semibold text-green-700 hover:text-green-800">← Kembali ke Produk Saya</a>
        <div class="mt-5 rounded-3xl bg-white p-6 shadow-sm sm:p-8">
            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">PRODUK BARU</span>
            <h1 class="mt-4 text-3xl font-extrabold">Tambahkan hasil panen</h1>
            <p class="mt-2 text-gray-500">Produk aktif akan tampil pada halaman pembeli dan landing page.</p>

            <form method="POST" action="{{ route('producer.products.store') }}" enctype="multipart/form-data" class="mt-8">
                @csrf
                @include('producer.products.form')
            </form>
        </div>
    </main>
</body>
</html>
