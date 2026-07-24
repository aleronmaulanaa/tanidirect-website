<section
    id="petani"
    class="scroll-mt-24 bg-[#F8FAF5] py-24 lg:py-32">


    <div class="mx-auto max-w-7xl px-6 lg:px-8">


        <div
            class="grid items-center gap-12 overflow-hidden rounded-[40px] bg-white p-8 shadow-xl lg:grid-cols-2 lg:p-12">



            {{-- Image --}}
            <div
                class="overflow-hidden rounded-3xl">


                <img
                    src="{{ asset('images/jualsayuran.png') }}"
                    alt="Petani TaniDirect"
                    class="h-[420px] w-full object-cover transition duration-500 hover:scale-105">


            </div>





            {{-- Content --}}
            <div>

                <h2
                    class="mt-6 text-4xl font-extrabold tracking-tight text-gray-900 lg:text-5xl">

                    Jual Hasil Panen
                    <span class="text-green-700">
                        Lebih Mudah
                    </span>

                </h2>




                <p
                    class="mt-5 text-lg leading-8 text-gray-600">

                    Hubungkan hasil tani Anda langsung dengan pembeli
                    melalui TaniDirect.

                </p>





                {{-- Benefit --}}
                <div
                    class="mt-8 space-y-4">


                    <div
                        class="flex items-center gap-3">


                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100 text-green-700">

                            ✓

                        </div>


                        <p class="text-gray-700">
                            Harga lebih transparan
                        </p>


                    </div>





                    <div
                        class="flex items-center gap-3">


                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100 text-green-700">

                            ✓

                        </div>


                        <p class="text-gray-700">
                            Jangkauan pembeli lebih luas
                        </p>


                    </div>





                    <div
                        class="flex items-center gap-3">


                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100 text-green-700">

                            ✓

                        </div>


                        <p class="text-gray-700">
                            Kelola produk dengan mudah
                        </p>


                    </div>


                </div>





                {{-- CTA --}}
                <div
                    class="mt-10">


                    @if(auth()->user()?->role === 'produsen')

                        <a
                            href="{{ route('producer.dashboard') }}"
                            class="inline-flex items-center justify-center rounded-xl bg-green-700 px-8 py-3 font-semibold text-white transition hover:bg-green-800">

                            Mulai Jual Produk

                        </a>


                    @else


                    <a
                        href="{{ route('producer.register') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-green-700 px-8 py-4 text-sm font-semibold text-white transition duration-300 hover:-translate-y-1 hover:bg-green-800 hover:shadow-xl">

                        Daftar Sebagai Petani

                    </a>


                    @endauth


                </div>


            </div>



        </div>


    </div>


</section>
