<section
    id="beranda"
    class="scroll-mt-24 relative overflow-hidden bg-gradient-to-br from-green-50 via-white to-white">

    {{-- Background Decoration --}}
    <div class="absolute inset-0 -z-10 overflow-hidden">

        <div
            class="absolute -top-40 -left-40 h-96 w-96 rounded-full bg-green-200/30 blur-3xl">
        </div>

        <div
            class="absolute top-1/3 right-0 h-[420px] w-[420px] rounded-full bg-emerald-100/40 blur-3xl">
        </div>

        <div
            class="absolute bottom-0 left-1/3 h-72 w-72 rounded-full bg-lime-100/30 blur-3xl">
        </div>

    </div>

    {{-- Hero Container --}}
<div
    class="mx-auto flex min-h-[calc(100vh-96px)] max-w-[1400px] flex-col items-center gap-20 px-6 py-16 lg:flex-row lg:justify-between lg:px-8 lg:py-24">

        {{-- ========================= --}}
        {{-- LEFT CONTENT --}}
        {{-- ========================= --}}
        <div class="w-full lg:w-[52%]">

            {{-- Badge --}}
            <div
                class="mb-8 inline-flex items-center gap-3 rounded-full border border-green-200 bg-white px-5 py-2 shadow-sm">

                <span class="h-2.5 w-2.5 rounded-full bg-green-600"></span>

                <span class="text-sm font-semibold tracking-wide text-green-700">
                    Menghubungkan Petani & UMKM Secara Langsung
                </span>

            </div>

            {{-- Heading --}}
            <h1
                class="text-5xl font-extrabold leading-[1.15] tracking-[-0.03em] text-gray-900 lg:text-6xl">

                Hasil Panen Langsung dari
                <span class="text-green-700">Petani,</span>

                <br>

                Harga Lebih Adil untuk Semua.

            </h1>

            {{-- Description --}}
            <p
                class="mt-8 max-w-2xl text-lg leading-9 text-gray-600">

               TaniDirect menghubungkan petani langsung dengan pembeli, UMKM, pedagang, rumah makan, dan konsumen. Tanpa perantara berlapis — harga transparan, hasil panen segar, petani lebih sejahtera.

            </p>
                        {{-- CTA Button --}}
            <div class="mt-10 flex flex-col gap-4 sm:flex-row">

                <a
                    href="#produk"
                    class="inline-flex items-center justify-center rounded-full bg-green-700 px-8 py-4 text-base font-semibold text-white transition-all duration-300 hover:bg-green-800 hover:shadow-xl">

                    Belanja Sekarang

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="ml-2 h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7"/>

                    </svg>

                </a>

<a
    href="#cara-kerja"
    class="inline-flex items-center justify-center rounded-full border border-gray-300 bg-white px-8 py-4 text-base font-semibold text-gray-700 transition-all duration-300 hover:border-green-700 hover:text-green-700">

    Pelajari Lebih Lanjut

    <svg
        xmlns="http://www.w3.org/2000/svg"
        class="ml-2 h-5 w-5"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor">

        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M9 5l7 7-7 7"/>

    </svg>

</a>

            </div>

            {{-- Statistics --}}
            <div class="mt-14 grid grid-cols-3 gap-8 border-t border-gray-200 pt-8">

                <div>

                    <h3 class="text-3xl font-bold text-green-700">
                        500+
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-gray-600">
                        Petani Bergabung
                    </p>

                </div>

                <div>

                    <h3 class="text-3xl font-bold text-green-700">
                        1.000+
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-gray-600">
                        UMKM Terlayani
                    </p>

                </div>

                <div>

                    <h3 class="text-3xl font-bold text-green-700">
                        50+
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-gray-600">
                        Komoditas Segar
                    </p>

                </div>

            </div>

        </div>
        {{-- ===================================== --}}
        {{-- RIGHT CONTENT --}}
        {{-- ===================================== --}}
        <div class="relative flex w-full items-center justify-center lg:w-[54%]">

            {{-- Background Decoration --}}
            <div
                class="absolute inset-0 rounded-[40px] bg-gradient-to-br from-green-100/50 to-emerald-50">
            </div>

            {{-- Hero Image --}}
            <div class="relative z-10 w-full">

                <img
                    src="{{ asset('images/hero.png') }}"
                    alt="Petani Indonesia"
                    class="aspect-[4/5] w-full rounded-[36px] object-cover shadow-2xl transition duration-500 hover:scale-[1.01]">

            </div>

        </div>
                {{-- Bottom Decoration --}}
        <div
            class="pointer-events-none absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-b from-transparent to-white">
        </div>

    </div>

</>
