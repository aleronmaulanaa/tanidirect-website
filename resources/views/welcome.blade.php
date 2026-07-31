<!DOCTYPE html>
<html 
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="scroll-smooth">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TaniDirect</title>


    {{-- Google Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">


    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-[#F8FAF5] font-sans antialiased">

    {{-- Navbar --}}
    @include('components.landing.navbar')

    {{-- Hero --}}
    @include('components.landing.hero')

    {{-- Features --}}
    @include('components.landing.features')

    {{-- How It Works --}}
    @include('components.landing.how-it-works')

    {{-- Products --}}
    @include('components.landing.products')

    {{-- Group Buy --}}
    @include('components.landing.group-buy')


    @include('components.landing.producer')

    {{-- Tentang --}}
    <section id="tentang" class="scroll-mt-24 bg-[#F8FAF5] py-24 lg:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <span class="inline-flex items-center rounded-full border border-green-200 bg-white px-5 py-2 text-sm font-semibold text-green-700 shadow-sm">
                    Tentang TaniDirect
                </span>
                <h2 class="mt-6 text-4xl font-extrabold tracking-tight text-gray-900 lg:text-5xl">
                    Menghubungkan Petani dan Pembeli dalam satu Ekosistem
                </h2>
                <p class="mt-5 text-lg leading-8 text-gray-600">
                    TaniDirect hadir untuk menciptakan pertukaran nilai yang adil, transparan, dan berkelanjutan antara produsen dan konsumen di Indonesia.
                </p>
            </div>

            <div class="relative mt-16 grid gap-8 md:grid-cols-3">
                <div class="group rounded-3xl border border-gray-200 bg-white p-8 text-center shadow-sm transition-all duration-300 hover:-translate-y-2 hover:border-green-300 hover:shadow-xl">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-green-100 text-green-700 transition-all duration-300 group-hover:bg-green-700 group-hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18l-2 13H5L3 3z" />
                        </svg>
                    </div>
                    <div class="mt-6 text-sm font-bold text-green-700">01</div>
                    <h3 class="mt-4 text-xl font-bold text-gray-900">Langsung dari Petani</h3>
                    <p class="mt-3 text-sm leading-6 text-gray-600">Harga transparan tanpa perantara. Pembeli dapat langsung berkomunikasi dengan produsen untuk memastikan kualitas dan kuantitas.</p>
                </div>

                <div class="group rounded-3xl border border-gray-200 bg-white p-8 text-center shadow-sm transition-all duration-300 hover:-translate-y-2 hover:border-green-300 hover:shadow-xl">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-green-100 text-green-700 transition-all duration-300 group-hover:bg-green-700 group-hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8z" />
                        </svg>
                    </div>
                    <div class="mt-6 text-sm font-bold text-green-700">02</div>
                    <h3 class="mt-4 text-xl font-bold text-gray-900">Pembelian Patungan</h3>
                    <p class="mt-3 text-sm leading-6 text-gray-600">Gabungkan kebutuhan dengan pembeli lain untuk mendapatkan harga grosir langsung dari petani tanpa minimum pembelian besar.</p>
                </div>

                <div class="group rounded-3xl border border-gray-200 bg-white p-8 text-center shadow-sm transition-all duration-300 hover:-translate-y-2 hover:border-green-300 hover:shadow-xl">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-green-100 text-green-700 transition-all duration-300 group-hover:bg-green-700 group-hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="mt-6 text-sm font-bold text-green-700">03</div>
                    <h3 class="mt-4 text-xl font-bold text-gray-900">Pantau Harga Pasar</h3>
                    <p class="mt-3 text-sm leading-6 text-gray-600">Akses data harga komoditas pertanian terkini dari sumber terpercaya untuk membantu keputusan belanjamu yang lebih tepat.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Call To Action --}}
    @include('components.landing.cta')
 
    {{-- Footer --}}
    @include('components.landing.footer')

    @include('components.chatbot.widget')

</body>
</html>