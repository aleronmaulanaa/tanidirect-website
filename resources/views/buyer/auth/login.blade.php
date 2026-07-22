<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pembeli | TaniDirect</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-green-50 flex items-center justify-center px-5">

<div class="w-full max-w-md rounded-3xl bg-white p-8 shadow-xl">

    <div class="mb-8 text-center">

        <img
            src="{{ asset('images/logo.png') }}"
            class="mx-auto mb-5 h-14"
            alt="TaniDirect"
        >

        <h1 class="text-2xl font-bold text-gray-900">
            Masuk Sebagai Pembeli
        </h1>

        <p class="mt-2 text-sm text-gray-500">
            Belanja hasil panen langsung dari petani.
        </p>

    </div>


    @if(session('success'))
        <div class="mb-4 rounded-xl bg-green-100 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif


    <form method="POST" action="{{ route('buyer.login.process') }}">
        @csrf

        <div class="space-y-4">

            <div>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Email"
                    class="w-full rounded-xl border-gray-300 px-4 py-3 focus:border-green-600 focus:ring-green-600"
                >

                @error('email')
                    <p class="mt-1 text-sm text-red-500">
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
                    <p class="mt-1 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

        </div>


        <button
            class="mt-6 w-full rounded-xl bg-green-600 py-3 font-semibold text-white transition hover:bg-green-700">

            Masuk

        </button>

    </form>


    <p class="mt-6 text-center text-sm text-gray-500">

        Belum punya akun?

        <a
            href="{{ route('buyer.register') }}"
            class="font-semibold text-green-600 hover:text-green-700">

            Daftar Sekarang

        </a>

    </p>

</div>

</body>

</html>