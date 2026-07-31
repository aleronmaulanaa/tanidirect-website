<section
    id="group-buy"
    class="scroll-mt-24 bg-[#F8FAF5] py-24 lg:py-32">


    <div class="mx-auto max-w-7xl px-6 lg:px-8">


        {{-- Header --}}
        <div class="text-center max-w-3xl mx-auto">


            <span
                class="inline-flex rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">

                Fitur Unggulan TaniDirect

            </span>


            <h2
                class="mt-6 text-4xl font-extrabold text-gray-900 lg:text-5xl">

                Beli Bersama,
                <span class="text-green-700">
                    Harga Lebih Hemat
                </span>

            </h2>


            <p
                class="mt-5 text-lg text-gray-600">

                Solusi bagi pembeli kecil untuk mendapatkan hasil panen langsung
                dari petani tanpa harus membeli dalam jumlah besar.

            </p>


        </div>





        {{-- Main Spotlight --}}
        <div
            class="mt-16 grid items-center gap-10 rounded-[40px] bg-white p-8 shadow-xl lg:grid-cols-2 lg:p-12">



            {{-- Visual --}}
            <div
                class="relative flex items-center justify-center rounded-3xl bg-green-50 p-10">


                <div
                    class="text-center">


                    <div
                        class="text-5xl font-extrabold text-green-700">

                        10 + 10 + 10

                    </div>


                    <div
                        class="mt-3 text-xl font-semibold text-gray-900">

                        kg pembelian bersama

                    </div>


                    <div
                        class="my-6 text-4xl">

                        ↓

                    </div>


                    <div
                        class="rounded-2xl bg-green-700 px-8 py-5 text-white">


                        <p class="text-sm">
                            Minimum Order Petani
                        </p>


                        <p class="text-4xl font-bold">
                            30 kg
                        </p>


                    </div>


                </div>


            </div>






            {{-- Information --}}
            <div>


                <h3
                    class="text-3xl font-bold text-gray-900">


                    Gabungkan Pesanan,
                    Dapatkan Harga Terbaik


                </h3>




                <p
                    class="mt-4 leading-7 text-gray-600">


                    Tidak perlu membeli dalam jumlah besar sendiri.
                    Beberapa pembeli dapat bergabung untuk memenuhi minimum
                    order petani.


                </p>





                {{-- Progress --}}
                @if($groupBuyData)


                <div
                    class="mt-8 rounded-3xl bg-green-50 p-6">


                    <div
                        class="flex justify-between">


                        <span class="text-gray-600">
                            Pesanan terkumpul
                        </span>


                        <span class="font-bold text-green-700">

                            {{ $groupBuyData['current'] }}
                            /
                            {{ $groupBuyData['target'] }} kg

                        </span>


                    </div>



                    <div
                        class="mt-4 h-4 rounded-full bg-white">


                        <div
                            class="h-full rounded-full bg-green-600"
                            style="width: {{ $groupBuyData['percentage'] }}%">
                        </div>


                    </div>



                    <p
                        class="mt-3 text-sm text-gray-600">


                        Tinggal
                        <b>
                            {{ $groupBuyData['remaining'] }} kg
                        </b>
                        lagi untuk memenuhi pesanan.


                    </p>


                </div>


                @endif





                {{-- CTA --}}
                <div
                    class="mt-8 flex flex-col gap-4 sm:flex-row">


                    <a
                        href="#cara-kerja"
                        class="rounded-xl border-2 border-green-700 px-7 py-3 text-center font-semibold text-green-700 hover:bg-green-50">


                        Lihat Cara Kerja


                    </a>



                    @if(auth()->user()?->role === 'pembeli')

                    <a
                        href="{{ route('order-pool.create') }}"
                        class="rounded-xl bg-green-700 px-7 py-3 text-center font-semibold text-white hover:bg-green-800">


                        Mulai Patungan


                    </a>


                    @else


                    <a
                        href="{{ route('buyer.register') }}"
                        class="rounded-xl bg-green-700 px-7 py-3 text-center font-semibold text-white hover:bg-green-800">


                        Mulai Patungan


                    </a>


                    @endauth


                </div>


            </div>



        </div>


    </div>


</section>
