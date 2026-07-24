<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk Saya | TaniDirect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f8f3] text-gray-900">
    <header class="border-b border-green-100 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 py-4 sm:px-8">
            <a href="{{ route('producer.dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="TaniDirect" class="h-9 w-auto">
                <span class="hidden border-l border-gray-200 pl-3 text-sm font-semibold text-gray-600 sm:inline">Dashboard Petani</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-sm font-semibold text-gray-600 hover:text-red-600">Logout</button>
            </form>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-5 py-10 sm:px-8">
        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-medium text-green-800">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-800">{{ session('error') }}</div>
        @endif

        <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
            <div>
                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">ETALASE SAYA</span>
                <h1 class="mt-4 text-3xl font-extrabold tracking-tight sm:text-4xl">Kelola produk hasil panen</h1>
                <p class="mt-2 max-w-xl text-gray-500">Hanya produk aktif yang dapat dilihat dan dibeli oleh pembeli.</p>
            </div>
            <a href="{{ route('producer.products.create') }}" class="inline-flex items-center justify-center rounded-xl bg-green-700 px-5 py-3 font-semibold text-white transition hover:bg-green-800">+ Tambah Produk</a>
        </div>

        @if($products->isEmpty())
            <section class="mt-8 rounded-3xl border-2 border-dashed border-green-200 bg-white px-6 py-16 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-green-100 text-2xl">🌾</div>
                <h2 class="mt-5 text-xl font-bold">Belum ada produk</h2>
                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-500">Tambahkan hasil panen pertama Anda agar pembeli dapat melihatnya di TaniDirect.</p>
                <a href="{{ route('producer.products.create') }}" class="mt-6 inline-flex rounded-xl bg-green-700 px-5 py-3 text-sm font-semibold text-white hover:bg-green-800">Tambah Produk Pertama</a>
            </section>
        @else
            <section class="mt-8 overflow-hidden rounded-3xl bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                            <tr>
                                <th class="px-5 py-4 font-semibold">Produk</th>
                                <th class="px-5 py-4 font-semibold">Harga</th>
                                <th class="px-5 py-4 font-semibold">Stok</th>
                                <th class="px-5 py-4 font-semibold">Status</th>
                                <th class="px-5 py-4 font-semibold"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($products as $product)
                                <tr>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($product->gambar)
                                                <img src="{{ asset('storage/'.$product->gambar) }}" alt="" class="h-12 w-12 rounded-xl object-cover">
                                            @else
                                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50 text-xl">🌾</div>
                                            @endif
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $product->nama_produk }}</p>
                                                <p class="mt-0.5 text-xs capitalize text-gray-500">{{ str_replace('_', ' ', $product->kategori) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 font-semibold text-green-700">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}/{{ $product->satuan }}</td>
                                    <td class="px-5 py-4 text-gray-600">{{ number_format($product->stok) }} {{ $product->satuan }}</td>
                                    <td class="px-5 py-4">
                                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('producer.products.edit', $product) }}" class="rounded-lg px-3 py-2 text-xs font-bold text-green-700 hover:bg-green-50">Edit</a>
                                            <form method="POST" action="{{ route('producer.products.status', $product) }}">
                                                @csrf @method('PATCH')
                                                <button class="rounded-lg px-3 py-2 text-xs font-bold text-gray-600 hover:bg-gray-100">{{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                            </form>
                                            <form method="POST" action="{{ route('producer.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?');">
                                                @csrf @method('DELETE')
                                                <button class="rounded-lg px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-50">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($products->hasPages())
                    <div class="border-t border-gray-100 px-5 py-4">{{ $products->links() }}</div>
                @endif
            </section>
        @endif
    </main>
</body>
</html>
