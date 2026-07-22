<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Petani | TaniDirect</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-green-50">

<div class="min-h-screen flex items-center justify-center px-6">

    <div class="grid w-full max-w-5xl overflow-hidden rounded-3xl bg-white shadow-xl md:grid-cols-2">


        <div class="hidden bg-green-700 p-10 text-white md:flex md:flex-col md:justify-between">

            <div>

                <h1 class="text-3xl font-bold leading-tight">
                    Jual Hasil Panen
                    <span class="text-green-200">
                        Lebih Mudah
                    </span>
                </h1>

                <p class="mt-5 text-sm leading-relaxed text-green-100">
                    Bergabung bersama TaniDirect untuk menjual hasil panen
                    langsung kepada pembeli dengan harga yang lebih transparan.
                </p>

            </div>


            <div>

                <p class="text-sm text-green-100">
                    🌱 Terhubung langsung dengan pembeli
                </p>

                <p class="mt-2 text-sm text-green-100">
                    📦 Kelola produk dan pesanan dengan mudah
                </p>

            </div>

        </div>



        <div class="p-8 md:p-12">


            <div class="mb-8">

                <h2 class="text-2xl font-bold text-gray-900">
                    Masuk Sebagai Petani
                </h2>

                <p class="mt-2 text-sm text-gray-500">
                    Kelola hasil panen dan penjualan Anda.
                </p>

            </div>


            <form method="POST" action="{{ route('producer.login.process') }}">

                @csrf


                <div class="mb-5">

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="w-full rounded-xl border-gray-300 focus:border-green-600 focus:ring-green-600"
                        placeholder="email@contoh.com"
                    >

                    @error('email')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>



                <div class="mb-6">

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="w-full rounded-xl border-gray-300 focus:border-green-600 focus:ring-green-600"
                        placeholder="********"
                    >

                </div>



                <button
                    class="w-full rounded-xl bg-green-600 py-3 font-semibold text-white transition hover:bg-green-700"
                >
                    Masuk Sebagai Petani
                </button>


            </form>



            <div class="mt-6 text-center text-sm text-gray-500">

                Belum memiliki akun?

                    <a 
                    href="{{ route('producer.register') }}"
                    class="font-semibold text-green-600 hover:text-green-700">

                    Daftar Sebagai Petani

                    </a>

            </div>


        </div>


    </div>

</div>


</body>

</html>