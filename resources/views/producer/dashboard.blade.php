    <!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard Petani | TaniDirect</title>

@vite(['resources/css/app.css','resources/js/app.js'])

</head>


<body class="bg-green-50">


<div class="min-h-screen p-8">


<div class="max-w-6xl mx-auto">


<div class="bg-white rounded-3xl shadow p-8">


<h1 class="text-3xl font-bold text-gray-900">

Halo, {{ auth()->user()->name }} 👋

</h1>


<p class="mt-2 text-gray-500">

Selamat datang di dashboard Mitra Petani TaniDirect.

</p>

<div class="flex items-center justify-between mb-8">


    <div>

        <h1 class="text-3xl font-bold text-gray-900">

            Halo, {{ auth()->user()->name }} 👋

        </h1>

        <p class="text-gray-500 mt-2">
            Selamat datang di dashboard Mitra Petani TaniDirect.
        </p>

    </div>



    <form method="POST" action="{{ route('producer.logout') }}">

        @csrf

        <button
            type="submit"
            class="rounded-xl bg-red-500 px-5 py-3 text-sm font-semibold text-white hover:bg-red-600 transition">

            Logout

        </button>

    </form>


</div>

<div class="grid md:grid-cols-3 gap-5 mt-8">


<div class="rounded-2xl bg-green-100 p-6">

<h3 class="font-semibold">
Produk Saya
</h3>

<p class="text-sm text-gray-600 mt-2">
Kelola hasil panen Anda.
</p>

</div>



<div class="rounded-2xl bg-green-100 p-6">

<h3 class="font-semibold">
Pesanan
</h3>

<p class="text-sm text-gray-600 mt-2">
Lihat permintaan pembeli.
</p>

</div>




<div class="rounded-2xl bg-green-100 p-6">

<h3 class="font-semibold">
Harga Referensi
</h3>

<p class="text-sm text-gray-600 mt-2">
Pantau harga komoditas.
</p>

</div>


</div>


</div>


</div>


</div>


</body>

</html>