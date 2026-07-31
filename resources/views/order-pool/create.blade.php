<x-app-layout>

    <x-slot name="header">

        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Buat Order Pool
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Mulai pembelian bersama untuk mendapatkan harga langsung dari petani.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">

            <div class="rounded-2xl bg-white p-6 shadow-sm dark:bg-gray-800">

                <form action="{{ route('order-pool.store') }}" method="POST">

                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Produk
                        </label>
                        <select
                            name="product_id"
                            required
                            class="mt-2 w-full rounded-xl border-gray-300 focus:border-green-600 focus:ring-green-600"
                        >
                            <option value="">Pilih produk...</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->nama_produk }}
                                    ({{ $product->producer->user->name }})
                                    — Rp {{ number_format($product->harga_jual, 0, ',', '.') }}
                                    /{{ $product->satuan }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700">
                            Target Volume (kg)
                        </label>
                        <input
                            type="number"
                            name="target_volume"
                            min="5"
                            value="5"
                            required
                            class="mt-2 w-full rounded-xl border-gray-300 focus:border-green-600 focus:ring-green-600"
                        >
                        <p class="mt-1 text-sm text-gray-500">
                            Minimal pembelian 5 kg
                        </p>
                        @error('target_volume')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700">
                            Batas Waktu
                        </label>
                        <input
                            type="datetime-local"
                            name="batas_waktu"
                            required
                            class="mt-2 w-full rounded-xl border-gray-300 focus:border-green-600 focus:ring-green-600"
                        >
                        <p class="mt-1 text-sm text-gray-500">
                            Tentukan batas waktu order pool ini aktif.
                        </p>
                        @error('batas_waktu')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-8 flex gap-3">
                        <a
                            href="{{ route('order-pool.index') }}"
                            class="rounded-xl border-2 border-gray-300 px-5 py-3 text-center font-semibold text-gray-700 transition hover:bg-gray-50"
                        >
                            Batal
                        </a>
                        <button
                            type="submit"
                            class="flex-1 rounded-xl bg-green-600 py-3 font-semibold text-white transition hover:bg-green-700"
                        >
                            Buat Order Pool
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>