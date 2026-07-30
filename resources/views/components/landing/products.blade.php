<section
    id="produk"
    class="scroll-mt-24 bg-[#F8FAF5] py-24 lg:py-32">


    <div class="mx-auto max-w-7xl px-6 lg:px-8">


        {{-- Heading --}}
        <div class="mx-auto max-w-3xl text-center">

            <span
                class="inline-flex items-center rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">

                Produk Unggulan

            </span>


            <h2
                class="mt-6 text-4xl font-extrabold tracking-tight text-gray-900 lg:text-5xl">

                Hasil Panen
                <span class="text-green-700">
                    Berkualitas
                </span>

            </h2>


            <p
                class="mt-5 text-lg leading-8 text-gray-600">

                Komoditas pilihan dengan harga transparan langsung dari petani.

            </p>

        </div>




        {{-- Product Grid --}}
        <div
            class="mt-16 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">



            {{-- ================================================= --}}
            {{-- PRODUK PETANI --}}
            {{-- ================================================= --}}


            @foreach($products as $product)


            <div
                class="group overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">


                {{-- Image --}}
                <div
                    class="h-48 overflow-hidden bg-green-50">


                    @if($product->gambar)

                        <img
                            src="{{ asset('storage/'.$product->gambar) }}"
                            alt="{{ $product->nama_produk }}"
                            class="h-full w-full object-cover transition duration-300 group-hover:scale-105">

                    @else

                        <img
                            src="{{ asset('images/logo.png') }}"
                            class="mx-auto h-full object-contain p-10">

                    @endif


                </div>




                <div class="p-6">


                    <span
                        class="text-xs font-semibold text-green-700">

                        Produk Petani

                    </span>



                    <h3
                        class="mt-2 text-xl font-bold text-gray-900">

                        {{ $product->nama_produk }}

                    </h3>



                    <p class="mt-2 text-sm text-gray-500">
                        {{ $product->producer->kabupaten_kota ?? 'Petani TaniDirect' }}
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




                    <div class="mt-5">


                        <p class="text-sm text-gray-500">
                            Harga Petani
                        </p>


                        <p
                            class="text-2xl font-bold text-green-700">

                            Rp {{ number_format($product->harga_jual,0,',','.') }}

                            <span class="text-sm font-normal text-gray-500">
                                /{{ $product->satuan }}
                            </span>

                        </p>


                    </div>




                    <div
                        class="mt-5 rounded-xl bg-green-50 px-4 py-3">


                        <p class="text-xs text-gray-500">
                            Stok tersedia
                        </p>


                        <p
                            class="font-semibold text-green-700">

                            {{ $product->stok }} {{ $product->satuan }}

                        </p>


                    </div>




                    <a
                        href="{{ auth()->user()?->role === 'pembeli' ? route('buyer.dashboard') : route('buyer.login') }}"
                        class="mt-5 inline-flex w-full items-center justify-center rounded-xl bg-green-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-green-800">


                        Lihat Produk


                    </a>


                </div>


            </div>


            @endforeach







            {{-- ================================================= --}}
            {{-- REFERENSI HARGA CSV --}}
            {{-- ================================================= --}}


            @foreach($priceReferences as $item)



            @php

                $gambar = match($item['kategori']) {

                    'BERAS MEDIUM'
                        => 'berasmedium.png',

                    'BERAS PREMIUM'
                        => 'beraspremium.png',

                    'JAGUNG PIPIL KERING'
                        => 'jagungpipilkering.png',

                    'KEDELAI'
                        => 'kedelai.png',

                    default
                        => 'logo.png'

                };

            @endphp





            <div
                class="group overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">


                {{-- Image --}}
                <div
                    class="h-48 overflow-hidden bg-green-50">


                    <img
                        src="{{ asset('images/'.$gambar) }}"
                        alt="{{ $item['kategori'] }}"
                        class="h-full w-full object-cover transition duration-300 group-hover:scale-105">


                </div>




                <div class="p-6">



                    <span
                        class="text-xs font-semibold text-green-700">

                        Harga Referensi

                    </span>




                    <h3
                        class="mt-2 text-xl font-bold text-gray-900">

                        {{ ucwords(strtolower($item['kategori'])) }}

                    </h3>




                    <p
                        class="mt-2 text-sm text-gray-500">

                        Estimasi harga langsung dari petani

                    </p>




                    <div
                        class="mt-5">


                        <p class="text-sm text-gray-500">

                            Harga Petani

                        </p>



                        <p
                            class="text-2xl font-bold text-green-700">

                            Rp {{ number_format($item['harga_produsen'],0,',','.') }}

                            <span class="text-sm font-normal text-gray-500">
                                /kg
                            </span>

                        </p>


                    </div>





                    @if($item['harga_konsumen'])


                    <div
                        class="mt-5 rounded-xl bg-green-50 px-4 py-3">


                        <p class="text-xs text-gray-500">

                            Harga Pasar

                        </p>


                        <p
                            class="font-semibold text-green-700">

                            Rp {{ number_format($item['harga_konsumen'],0,',','.') }}/kg

                        </p>


                    </div>


                    @endif





                    <div
                        class="mt-4 flex items-center justify-between text-sm">


                        <span class="text-gray-500">

                            Minimum Order

                        </span>


                        <span class="font-semibold text-gray-900">

                            30 kg

                        </span>


                    </div>




                    <a
                        href="{{ auth()->user()?->role === 'pembeli' ? route('buyer.dashboard') : route('buyer.register') }}"
                        class="mt-5 inline-flex w-full items-center justify-center rounded-xl bg-green-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-green-800">


                        Mulai Belanja


                    </a>



                </div>


            </div>



            @endforeach



        </div>


    </div>


</section>
