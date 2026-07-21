<x-app-layout>

    <x-slot name="header">


        @if(session('success'))

        <div class="mb-5 rounded-xl bg-green-100 p-4 text-green-700">
            {{ session('success') }}
        </div>

        @endif


        @if(session('error'))

        <div class="mb-5 rounded-xl bg-red-100 p-4 text-red-700">
            {{ session('error') }}
        </div>

        @endif

        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Detail Order Pool
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Informasi lengkap pembelian bersama.
            </p>
        </div>
    </x-slot>


    <div class="py-8">

        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

            <div class="rounded-2xl bg-white p-6 shadow-sm dark:bg-gray-800">


                <div class="flex justify-between items-start">

                    <div>

                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $orderPool->product->nama_produk }}
                        </h3>

                        <p class="mt-2 text-gray-500">
                            Harga langsung dari produsen
                        </p>

                    </div>


                    <span class="rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">
                        {{ ucfirst($orderPool->status) }}
                    </span>


                </div>


                <div class="mt-6">

                    <p class="text-3xl font-bold text-green-700">
                        Rp {{ number_format($orderPool->product->harga_jual,0,',','.') }}
                        <span class="text-base font-normal text-gray-500">
                            /{{ $orderPool->product->satuan }}
                        </span>
                    </p>

                </div>


                <div class="mt-6 grid gap-4 md:grid-cols-3">


                    <div class="rounded-xl bg-gray-50 p-4">

                        <p class="text-sm text-gray-500">
                            Target Pembelian
                        </p>

                        <p class="mt-2 text-xl font-bold">
                            {{ $orderPool->target_volume }} kg
                        </p>

                    </div>


                    <div class="rounded-xl bg-gray-50 p-4">

                        <p class="text-sm text-gray-500">
                            Terkumpul
                        </p>

                        <p class="mt-2 text-xl font-bold">
                            {{ $orderPool->volume_terkumpul }} kg
                        </p>

                    </div>


                    <div class="rounded-xl bg-gray-50 p-4">

                        <p class="text-sm text-gray-500">
                            Peserta
                        </p>

                        <p class="mt-2 text-xl font-bold">
                            {{ $orderPool->members->count() }} orang
                        </p>

                    </div>


                </div>


                <div class="mt-8">

                    <h4 class="font-semibold text-lg">
                        Informasi Produsen
                    </h4>


                    <div class="mt-3 text-gray-600">

                        <p>
                            Nama:
                            {{ $orderPool->product->producer->user->name }}
                        </p>

                        <p>
                            Lokasi:
                            {{ $orderPool->product->producer->kabupaten_kota }}
                        </p>

                    </div>

                </div>


                <div class="mt-8">

                    <form action="{{ route('order-pool.join', $orderPool) }}" method="POST">

                        @csrf

                        <div class="mt-6">

                            <label class="block text-sm font-medium text-gray-700">
                                Jumlah Pembelian (kg)
                            </label>

                            <input
                                type="number"
                                name="jumlah"
                                min="5"
                                max="{{ $orderPool->target_volume - $orderPool->volume_terkumpul }}"
                                value="5"
                                class="mt-2 w-full rounded-xl border-gray-300 focus:border-green-600 focus:ring-green-600"
                                required
                            >

                            <p class="mt-2 text-sm text-gray-500">
                                Minimal pembelian 5 kg
                            </p>

                        </div>


                        <button
                            class="mt-5 w-full rounded-xl bg-green-600 py-3 font-semibold text-white hover:bg-green-700"
                        >
                            Gabung Order Pool
                        </button>

                    </form>

                </div>


            </div>

        </div>

    </div>

</x-app-layout>