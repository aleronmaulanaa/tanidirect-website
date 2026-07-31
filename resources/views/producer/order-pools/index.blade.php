<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Order Pool Saya
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Kelola order pool untuk produk yang Anda jual.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

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
                                            {{ $orderPool->product->producer->user->name ?? 'Petani TaniDirect' }}
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
                                        href="{{ route('producer.order-pools.show', $orderPool) }}"
                                        class="block rounded-xl bg-green-600 py-3 text-center font-medium text-white transition hover:bg-green-700"
                                    >
                                        Lihat Detail
                                    </a>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $orderPools->links() }}
                </div>

            @else

                <div class="rounded-2xl bg-white py-16 text-center shadow-sm dark:bg-gray-800">

                    <h3 class="text-lg font-semibold">
                        Belum ada Order Pool
                    </h3>

                    <p class="mt-2 text-gray-500">
                        Order pool akan muncul di sini ketika pembeli membuatnya untuk produk Anda.
                    </p>

                </div>

            @endif

        </div>
    </div>
</x-app-layout>