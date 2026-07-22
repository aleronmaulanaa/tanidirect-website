<header
    x-data="{ open: false }"
    class="sticky top-0 z-50 w-full border-b border-gray-100 bg-white/95 shadow-sm backdrop-blur">

    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:h-20 sm:px-6 lg:h-24 lg:px-8">

        {{-- Logo --}}
        <a href="{{ url('/') }}" class="flex items-center flex-shrink-0">

            <img
                src="{{ asset('images/logo.png') }}"
                alt="TaniDirect"
                class="h-10 w-auto object-contain transition duration-300 hover:scale-105 sm:h-12 lg:h-16">

        </a>

        {{-- Desktop Menu --}}
        <nav class="hidden items-center gap-6 xl:gap-10 lg:flex">

            <a
                href="#beranda"
                class="text-sm font-medium text-gray-700 transition hover:text-green-700 xl:text-base">
                Beranda
            </a>

            <a
                href="#produk"
                class="text-sm font-medium text-gray-700 transition hover:text-green-700 xl:text-base">
                Produk
            </a>

            <a
                href="#cara-kerja"
                class="text-sm font-medium text-gray-700 transition hover:text-green-700 xl:text-base">
                Cara Kerja
            </a>

            <a
                href="#tentang"
                class="text-sm font-medium text-gray-700 transition hover:text-green-700 xl:text-base">
                Tentang
            </a>

            <a
                href="#kontak"
                class="text-sm font-medium text-gray-700 transition hover:text-green-700 xl:text-base">
                Kontak
            </a>

        </nav>

        {{-- Desktop Button --}}
        <div class="hidden items-center gap-3 xl:gap-4 lg:flex">

            <a
                href="{{ route('buyer.login') }}"
                class="rounded-full px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100 xl:px-5 xl:py-3 xl:text-base">

                Masuk

            </a>

            <a
                href="{{ route('buyer.register') }}"
                class="rounded-full bg-green-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-green-800 xl:px-7 xl:py-3 xl:text-base">

                Daftar Sekarang

            </a>

        </div>

        {{-- Mobile Button --}}
        <button
            @click="open = !open"
            class="flex h-10 w-10 items-center justify-center rounded-xl transition hover:bg-gray-100 focus:outline-none lg:hidden">

            {{-- Hamburger --}}
            <svg
                x-show="!open"
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"/>

            </svg>

            {{-- Close --}}
            <svg
                x-show="open"
                x-cloak
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"/>

            </svg>

        </button>

    </div>

    {{-- Mobile Menu --}}
    <div
        x-show="open"
        x-cloak
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="border-t border-gray-200 bg-white shadow-lg lg:hidden">

        <div class="space-y-2 px-4 py-5 sm:px-6">

            <a
                href="#beranda"
                @click="open = false"
                class="block rounded-xl px-4 py-3 text-base font-medium text-gray-700 transition hover:bg-green-50 hover:text-green-700">

                Beranda

            </a>

            <a
                href="#produk"
                @click="open = false"
                class="block rounded-xl px-4 py-3 text-base font-medium text-gray-700 transition hover:bg-green-50 hover:text-green-700">

                Produk

            </a>

            <a
                href="#cara-kerja"
                @click="open = false"
                class="block rounded-xl px-4 py-3 text-base font-medium text-gray-700 transition hover:bg-green-50 hover:text-green-700">

                Cara Kerja

            </a>

            <a
                href="#tentang"
                @click="open = false"
                class="block rounded-xl px-4 py-3 text-base font-medium text-gray-700 transition hover:bg-green-50 hover:text-green-700">

                Tentang

            </a>

            <a
                href="#kontak"
                @click="open = false"
                class="block rounded-xl px-4 py-3 text-base font-medium text-gray-700 transition hover:bg-green-50 hover:text-green-700">

                Kontak

            </a>

            <div class="grid grid-cols-2 gap-3 pt-5">

                <a
                    href="{{ route('buyer.login') }}"
                    class="rounded-full border border-gray-300 py-3 text-center text-sm font-medium text-gray-700 transition hover:bg-gray-100">

                    Masuk

                </a>

                <a
                    href="{{ route('buyer.register') }}"
                    class="rounded-full bg-green-700 py-3 text-center text-sm font-semibold text-white transition hover:bg-green-800">

                    Daftar Sekarang

                </a>

            </div>

        </div>

    </div>

</header>