<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Mitra Petani | TaniDirect</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>


<body class="min-h-screen bg-green-50">


<div class="min-h-screen flex items-center justify-center px-5 py-10">


    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-xl overflow-hidden grid md:grid-cols-2">



        <!-- LEFT SECTION -->

        <div class="bg-green-700 text-white p-10 flex flex-col justify-center">


            <img
                src="{{ asset('images/logo.png') }}"
                class="w-32 mb-10 brightness-0 invert"
                alt="TaniDirect"
            >



            <span class="text-sm bg-green-600 rounded-full px-4 py-2 w-fit">

                🌱 Mitra Petani TaniDirect

            </span>




            <h1 class="mt-6 text-4xl font-bold leading-tight">

                Jual Hasil Panen
                <span class="text-green-200">
                    Lebih Mudah
                </span>

            </h1>




            <p class="mt-5 text-green-100 text-sm leading-relaxed">

                Hubungkan hasil panen langsung dengan pembeli
                dan dapatkan harga yang lebih transparan.

            </p>





            <div class="mt-8 space-y-4">


                <div class="flex gap-3 items-center">

                    <span class="text-xl">
                        ✓
                    </span>

                    <p>
                        Harga lebih transparan
                    </p>

                </div>



                <div class="flex gap-3 items-center">

                    <span class="text-xl">
                        ✓
                    </span>

                    <p>
                        Pasar pembeli lebih luas
                    </p>

                </div>




                <div class="flex gap-3 items-center">

                    <span class="text-xl">
                        ✓
                    </span>

                    <p>
                        Kelola produk dengan mudah
                    </p>

                </div>


            </div>



        </div>






        <!-- RIGHT SECTION -->


        <div class="p-8 md:p-12">


            <div class="text-center mb-8">


                <h2 class="text-2xl font-bold text-gray-900">

                    Daftar Sebagai Petani

                </h2>



                <p class="text-sm text-gray-500 mt-2">

                    Mulai menjual hasil panen Anda.

                </p>


            </div>


            <form method="POST" action="{{ route('producer.register.process') }}">

                @csrf

                <div class="space-y-4">

                    <div>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Nama lengkap"
                            class="w-full rounded-xl border-gray-300 px-4 py-3 focus:border-green-600 focus:ring-green-600"
                        >

                        @error('name')
                            <p class="text-sm text-red-500 mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    <div>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Email"
                            class="w-full rounded-xl border-gray-300 px-4 py-3 focus:border-green-600 focus:ring-green-600"
                        >


                        @error('email')
                            <p class="text-sm text-red-500 mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>






                    <div>

                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="Nomor WhatsApp"
                            class="w-full rounded-xl border-gray-300 px-4 py-3 focus:border-green-600 focus:ring-green-600"
                        >


                        @error('phone')
                            <p class="text-sm text-red-500 mt-1">
                                {{ $message }}
                            </p>
                        @enderror


                    </div>







                    <div>


                        <input
                            type="text"
                            name="kabupaten_kota"
                            value="{{ old('kabupaten_kota') }}"
                            placeholder="Kabupaten/Kota"
                            class="w-full rounded-xl border-gray-300 px-4 py-3 focus:border-green-600 focus:ring-green-600"
                        >


                        @error('kabupaten_kota')
                            <p class="text-sm text-red-500 mt-1">
                                {{ $message }}
                            </p>
                        @enderror


                    </div>







                    <div>


                        <select
                            name="komoditas_utama"
                            class="w-full rounded-xl border-gray-300 px-4 py-3 focus:border-green-600 focus:ring-green-600"
                        >


                            <option value="">
                                Pilih Komoditas
                            </option>


                            <option value="Beras">
                                Beras
                            </option>


                            <option value="Jagung">
                                Jagung
                            </option>


                            <option value="Kedelai">
                                Kedelai
                            </option>



                        </select>



                        @error('komoditas_utama')

                            <p class="text-sm text-red-500 mt-1">

                                {{ $message }}

                            </p>

                        @enderror



                    </div>







                    <div>


                        <input
                            type="password"
                            name="password"
                            placeholder="Password"
                            class="w-full rounded-xl border-gray-300 px-4 py-3 focus:border-green-600 focus:ring-green-600"
                        >


                        @error('password')

                            <p class="text-sm text-red-500 mt-1">

                                {{ $message }}

                            </p>

                        @enderror



                    </div>





                </div>






                <button
                    type="submit"
                    class="mt-6 w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl transition">


                    Daftar & Mulai Menjual


                </button>





            </form>






            <p class="text-center text-sm text-gray-500 mt-6">


                Sudah punya akun?


                <a
                    href="{{ route('producer.login') }}"
                    class="text-green-600 font-semibold hover:text-green-700">


                    Login


                </a>


            </p>




        </div>



    </div>


</div>



</body>

</html>