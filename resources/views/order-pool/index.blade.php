<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Order Pool
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Gabung pembelian bersama untuk mendapatkan harga langsung dari petani.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="mb-8 rounded-2xl bg-white p-6 shadow-sm dark:bg-gray-800">

    <form method="GET" action="{{ route('order-pool.index') }}">
        <div class="grid gap-4 md:grid-cols-3">

            <div class="md:col-span-2">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari produk..."
                    class="w-full rounded-xl border-gray-300 focus:border-green-600 focus:ring-green-600"
                >
            </div>

            <div>
                <select
                    name="status"
                    class="w-full rounded-xl border-gray-300 focus:border-green-600 focus:ring-green-600"
                >
                    <option value="">Semua Status</option>

                    <option
                        value="open"
                        @selected($status == 'open')
                    >
                        Open
                    </option>

                    <option
                        value="fulfilled"
                        @selected($status == 'fulfilled')
                    >
                        Fulfilled
                    </option>

                    <option
                        value="closed"
                        @selected($status == 'closed')
                    >
                        Closed
                    </option>

                </select>
            </div>

        </div>

        <div class="mt-4 flex justify-end">
            <button
                class="rounded-xl bg-green-600 px-5 py-2 text-white transition hover:bg-green-700"
            >
                Terapkan
            </button>
        </div>

    </form>

</div>

            @if ($orderPools->count())

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">

    @foreach ($orderPools as $orderPool)

        @php
            $progress = min(
                100,
                ($orderPool->volume_terkumpul / max($orderPool->target_volume, 1)) * 100
            );
        @endphp

        <div
            class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg dark:border-gray-700 dark:bg-gray-800"
        >

            <div class="p-6">

                <div class="flex items-start justify-between">

                    <div>

                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $orderPool->product->nama_produk }}
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Harga langsung dari petani
                        </p>

                    </div>

                    @if($orderPool->status == 'open')

                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                            Open
                        </span>

                    @elseif($orderPool->status == 'fulfilled')

                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                            Fulfilled
                        </span>

                    @else

                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                            Closed
                        </span>

                    @endif

                </div>

                <div class="mt-6">

                    <p class="text-2xl font-bold text-green-700">
                        Rp {{ number_format($orderPool->product->harga_jual, 0, ',', '.') }}
                        <span class="text-base font-normal text-gray-500">
                            /{{ $orderPool->product->satuan }}
                        </span>
                    </p>

                </div>

                <div class="mt-6">

                    <div class="mb-2 flex justify-between text-sm">

                        <span>Progress Pool</span>

                        <span>{{ round($progress) }}%</span>

                    </div>

                    <div class="h-3 overflow-hidden rounded-full bg-gray-200">

                        <div
                            class="h-full rounded-full bg-green-600"
                            @style([
                                "width: {$progress}%"
                            ])
                        ></div>

                    </div>

                    <p class="mt-2 text-sm text-gray-500">
                        {{ $orderPool->volume_terkumpul }}
                        /
                        {{ $orderPool->target_volume }}
                        kg
                    </p>

                </div>

                @if($orderPool->batas_waktu)

                    <div class="mt-5 flex items-center justify-between text-sm text-gray-500">

                        <span>Deadline</span>

                        <span>
                            {{ $orderPool->batas_waktu->format('d M Y') }}
                        </span>

                    </div>

                @endif

                <div class="mt-6">

                    <a
                        href="{{ route('order-pool.show', $orderPool) }}"
                        class="block rounded-xl bg-green-600 py-3 text-center font-medium text-white transition hover:bg-green-700"
                    >
                        Lihat Detail
                    </a>

                </div>

            </div>

        </div>

    @endforeach

    </div>

@else

    <div class="rounded-2xl bg-white py-16 text-center shadow-sm dark:bg-gray-800">

        <h3 class="text-lg font-semibold">
            Belum ada Order Pool
        </h3>

        <p class="mt-2 text-gray-500">
            Silakan kembali lagi nanti.
        </p>

    </div>

@endif

            {{-- Pagination disini --}}

        </div>
    </div>
</x-app-layout>