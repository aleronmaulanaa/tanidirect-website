<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>
        Dashboard Pembeli | TaniDirect
    </title>


    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>



<body class="min-h-screen bg-[#F6F8F3]">



{{-- Navbar --}}

<header class="sticky top-0 z-50 border-b border-gray-100 bg-white/90 backdrop-blur">


    <div
        class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">


        <a href="{{ route('landing') }}">

            <img
                src="{{ asset('images/logo.png') }}"
                alt="TaniDirect"
                class="h-10">

        </a>




        <nav class="hidden gap-8 text-sm font-medium text-gray-600 md:flex">


            <a href="#produk"
                class="transition hover:text-green-700">

                Produk

            </a>


            <a href="#komoditas"
                class="transition hover:text-green-700">

                Komoditas

            </a>


            <a href="#patungan"
                class="transition hover:text-green-700">

                Patungan

            </a>



        </nav>




        <div class="flex items-center gap-3">
            <a href="{{ route('profile') }}" class="rounded-xl border border-green-200 px-4 py-2 text-sm font-semibold text-green-700 transition hover:bg-green-50">
                Profil
            </a>

            <form
                method="POST"
                action="{{ route('logout') }}">


                @csrf


                <button
                    class="rounded-xl bg-green-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-green-800">


                    Logout


                </button>


            </form>
        </div>



    </div>


</header>








<main class="mx-auto max-w-7xl px-6 py-10">

@if(session('success'))
<div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-800">
    {{ session('success') }}
</div>
@endif





{{-- Hero --}}

<section
    class="relative overflow-hidden rounded-[2rem] bg-gradient-to-r from-green-700 via-green-600 to-emerald-500 p-10 text-white shadow-xl">


    <div class="relative z-10 max-w-2xl">


        <span
            class="inline-flex rounded-full bg-white/20 px-4 py-2 text-sm font-semibold">


            Dashboard Pembeli


        </span>




        <h1
            class="mt-6 text-4xl font-extrabold leading-tight lg:text-5xl">


            Halo, {{ $buyer->name }}


        </h1>




        <p
            class="mt-4 text-lg leading-relaxed text-green-50">


            Temukan hasil pertanian berkualitas langsung dari petani
            dengan harga yang lebih transparan.


        </p>




        <div class="mt-8 flex flex-wrap gap-4">


            <a
                href="#produk"
                class="rounded-xl bg-white px-6 py-3 font-semibold text-green-700 transition hover:scale-105">


                Cari Produk


            </a>




            <a
                href="#patungan"
                class="rounded-xl border border-white/50 px-6 py-3 font-semibold text-white transition hover:bg-white/10">


                Mulai Patungan


            </a>


        </div>


    </div>




    <div
        class="absolute -right-20 -top-20 h-72 w-72 rounded-full bg-white/10">


    </div>



</section>










{{-- Statistik --}}

<section
    class="mt-10 grid gap-6 md:grid-cols-3">



<div
    class="rounded-3xl bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">


    <p
        class="text-sm font-medium text-gray-500">


        Produk Petani


    </p>



    <h2
        class="mt-3 text-4xl font-extrabold text-green-700">


        {{ $totalProducts }}


    </h2>



    <p
        class="mt-2 text-sm text-gray-500">


        Produk langsung dari produsen


    </p>


</div>





<div
    class="rounded-3xl bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">


    <p
        class="text-sm font-medium text-gray-500">


        Patungan Aktif


    </p>



    <h2
        class="mt-3 text-4xl font-extrabold text-green-700">


        {{ $totalOrderPools }}


    </h2>



    <p
        class="mt-2 text-sm text-gray-500">


        Pembelian bersama pengguna lain


    </p>


</div>





<div
    class="rounded-3xl bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">


    <p
        class="text-sm font-medium text-gray-500">


        Pesanan Saya


    </p>



    <h2
        class="mt-3 text-4xl font-extrabold text-green-700">


        {{ $totalOrders }}


    </h2>



    <p
        class="mt-2 text-sm text-gray-500">


        Riwayat transaksi pembelian


    </p>


</div>



</section>









{{-- Produk Petani --}}

<section
    id="produk"
    class="mt-14">



<div>


<h2
    class="text-3xl font-extrabold text-gray-900">


    Produk Dari Petani


</h2>



<p
    class="mt-2 text-gray-500">


    Hasil panen langsung dari produsen terpercaya.


</p>


</div>






<div
    class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">



@forelse($products as $product)



<div
    class="group overflow-hidden rounded-3xl bg-white shadow-sm transition duration-300 hover:-translate-y-2 hover:shadow-xl">



<div
    class="h-44 bg-green-50">


@if($product->gambar)


<img
    src="{{ asset('storage/'.$product->gambar) }}"
    class="h-full w-full object-cover transition duration-300 group-hover:scale-105">


@else


<div
    class="flex h-full items-center justify-center">


    <img
        src="{{ asset('images/logo.png') }}"
        class="h-20">


</div>


@endif



</div>





<div
    class="p-5">


<span
    class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">


    Langsung Petani


</span>



<h3
    class="mt-4 font-bold text-gray-900">


    {{ $product->nama_produk }}


</h3>




<p class="mt-2 text-sm text-gray-500">
    {{ $product->producer->kabupaten_kota ?? 'Indonesia' }}
</p>

{{-- Rating Bintang --}}
@php
    $avgRating = $product->average_rating;
    $totalReviews = $product->total_reviews;
@endphp
<div class="mt-2 flex items-center gap-1.5 text-xs font-semibold">
    @if($totalReviews > 0)
        <span class="text-amber-500">⭐</span>
        <span class="text-gray-900 font-bold">{{ number_format($avgRating, 1) }}</span>
        <span class="font-normal text-gray-400">({{ $totalReviews }})</span>
    @else
        <span class="font-normal text-gray-400">⭐ Belum ada ulasan</span>
    @endif
</div>




<p
    class="mt-4 text-xl font-extrabold text-green-700">


    Rp {{ number_format($product->harga_jual,0,',','.') }}


    <span
        class="text-sm font-normal text-gray-500">


        /{{ $product->satuan }}


    </span>


</p>

<a href="{{ route('buyer.products.show', $product) }}"
    class="mt-5 inline-flex w-full items-center justify-center rounded-xl bg-green-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-green-800">
    Lihat & Pesan Produk
</a>


</div>


</div>



@empty


<p class="text-gray-500">

Belum ada produk petani.

</p>


@endforelse



</div>


</section>









{{-- Komoditas Unggulan --}}

<section
    id="komoditas"
    class="mt-14">



<h2
    class="text-3xl font-extrabold text-gray-900">


    Komoditas Unggulan


</h2>



<p
    class="mt-2 text-gray-500">


    Referensi harga berdasarkan data pasar.


</p>






<div
    class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">



@foreach($priceReferences->take(4) as $item)



@php

$gambar = match($item['kategori']) {

    'BERAS MEDIUM' => 'berasmedium.png',

    'BERAS PREMIUM' => 'beraspremium.png',

    'JAGUNG PIPIL KERING' => 'jagungpipilkering.png',

    'KEDELAI' => 'kedelai.png',

    default => 'logo.png'

};

@endphp






<div
    class="overflow-hidden rounded-3xl bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">



<div
    class="h-44 bg-green-50">


<img
    src="{{ asset('images/'.$gambar) }}"
    class="h-full w-full object-cover">


</div>





<div
    class="p-5">


<h3
    class="font-bold text-gray-900">


    {{ ucwords(strtolower($item['kategori'])) }}


</h3>




<p
    class="mt-3 text-sm text-gray-500">


    Harga petani


</p>



<p
    class="mt-2 text-2xl font-extrabold text-green-700">


Rp {{ number_format($item['harga_produsen'],0,',','.') }}



<span
class="text-sm font-normal text-gray-500">

/kg

</span>


</p>



</div>


</div>




@endforeach



</div>


</section>

{{-- Pesanan Saya --}}

<section id="pesanan" class="mt-14">
    <div class="flex items-end justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900">Pesanan Saya</h2>
            <p class="mt-2 text-gray-500">Pantau pesanan langsung yang telah Anda buat.</p>
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-3xl bg-white shadow-sm">
        @forelse($orders as $order)
            <div class="flex flex-col gap-4 border-b border-gray-100 p-5 last:border-0 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="font-bold text-gray-900">{{ $order->product->nama_produk }}</p>
                    <p class="mt-1 text-sm text-gray-500">{{ $order->jumlah }} {{ $order->product->satuan }} · {{ $order->created_at->format('d M Y') }}</p>
                </div>
                <div class="flex items-center gap-3 sm:text-right">
                    <div>
                        <p class="font-bold text-green-700">Rp {{ number_format($order->grand_total ?: $order->total_harga, 0, ',', '.') }}</p>
                        @php
                            $statusColors = [
                                'dipesan'  => 'bg-amber-100 text-amber-700',
                                'diproses' => 'bg-blue-100 text-blue-700',
                                'dikirim'  => 'bg-indigo-100 text-indigo-700',
                                'diterima' => 'bg-green-100 text-green-700',
                            ];
                            $badgeClass = $statusColors[$order->status_pengiriman] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <span class="mt-1 inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $badgeClass }}">{{ ucfirst($order->status_pengiriman) }}</span>
                    </div>
                    <a href="{{ route('buyer.orders.tracking', $order) }}"
                       class="rounded-xl border border-green-200 px-4 py-2 text-xs font-bold text-green-700 transition hover:bg-green-50">
                        Lacak →
                    </a>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-sm text-gray-500">Belum ada pesanan. Pilih produk petani untuk mulai berbelanja.</div>
        @endforelse
    </div>
</section>









{{-- Patungan --}}

<section
    id="patungan"
    class="mt-14 pb-10">


<h2
    class="text-3xl font-extrabold text-gray-900">


    Patungan Berjalan


</h2>




<div
    class="mt-6 rounded-3xl bg-white p-8 shadow-sm">



@if($orderPools->count())


@foreach($orderPools as $pool)


<p class="font-semibold">

{{ $pool->product->nama_produk }}

</p>


@endforeach



@else


<h3
    class="text-xl font-bold text-gray-900">


    Belum ada patungan aktif


</h3>


<p
    class="mt-2 text-gray-500">


    Gabungkan pembelian dengan pengguna lain untuk mendapatkan harga lebih hemat.


</p>


@endif



</div>


</section>





</main>



</body>


</html>
