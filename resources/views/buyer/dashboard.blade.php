<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Pembeli | TaniDirect</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-green-50">

<header class="bg-white shadow-sm border-b">

    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5">

        <div class="flex items-center gap-3">

            <img
                src="{{ asset('images/logo.png') }}"
                class="h-10"
                alt="TaniDirect"
            >

        </div>


        <form method="POST" action="{{ route('logout') }}">

            @csrf

            <button
                class="rounded-xl bg-green-600 px-5 py-2 text-sm font-semibold text-white hover:bg-green-700">

                Logout

            </button>

        </form>

    </div>

</header>


<main class="mx-auto max-w-7xl px-6 py-10">


    <div class="rounded-3xl bg-white p-8 shadow">


        <h1 class="text-3xl font-bold text-gray-900">

            Halo, {{ Auth::user()->name }} 👋

        </h1>


        <p class="mt-2 text-gray-500">

            Selamat datang di dashboard pembeli TaniDirect.

        </p>


    </div>



    <div class="mt-8 grid gap-6 md:grid-cols-3">


        <div class="rounded-2xl bg-white p-6 shadow">

            <h3 class="font-semibold text-gray-900">
                Produk Pertanian
            </h3>

            <p class="mt-2 text-sm text-gray-500">
                Cari hasil panen terbaik langsung dari petani.
            </p>

        </div>



        <div class="rounded-2xl bg-white p-6 shadow">

            <h3 class="font-semibold text-gray-900">
                Pesanan Saya
            </h3>

            <p class="mt-2 text-sm text-gray-500">
                Pantau status pesanan Anda.
            </p>

        </div>



        <div class="rounded-2xl bg-white p-6 shadow">

            <h3 class="font-semibold text-gray-900">
                Patungan Order
            </h3>

            <p class="mt-2 text-sm text-gray-500">
                Gabung pesanan bersama pembeli lain.
            </p>

        </div>


    </div>


</main>


</body>

</html>