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

                    <h4 class="font-semibold text-lg">
                        Anggota Order Pool
                    </h4>

                    <div class="mt-3 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-gray-100 bg-gray-50/70">
                                    <th class="px-6 py-4 font-semibold text-gray-600">Pembeli</th>
                                    <th class="px-6 py-4 font-semibold text-gray-600 text-right">Jumlah (kg)</th>
                                    <th class="px-6 py-4 font-semibold text-gray-600">Bergabung</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($orderPool->members as $member)
                                    <tr class="transition hover:bg-green-50/30">
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            {{ $member->buyer->name }}
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-green-700">
                                            {{ $member->jumlah }} kg
                                        </td>
                                        <td class="px-6 py-4 text-gray-500">
                                            {{ $member->bergabung_pada->format('d M Y, H:i') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-10 text-center text-gray-500">
                                            Belum ada anggota yang bergabung.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>


                @if($orderPool->orders->count())

                <div class="mt-8">

                    <h4 class="font-semibold text-lg">
                        Pesanan Terkait
                    </h4>

                    <div class="mt-3 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-gray-100 bg-gray-50/70">
                                    <th class="px-6 py-4 font-semibold text-gray-600">Pembeli</th>
                                    <th class="px-6 py-4 font-semibold text-gray-600 text-right">Jumlah (kg)</th>
                                    <th class="px-6 py-4 font-semibold text-gray-600 text-right">Total</th>
                                    <th class="px-6 py-4 font-semibold text-gray-600 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($orderPool->orders as $order)
                                    @php
                                        $statusColors = [
                                            'dipesan'  => 'bg-amber-100 text-amber-700',
                                            'diproses' => 'bg-blue-100 text-blue-700',
                                            'dikirim'  => 'bg-indigo-100 text-indigo-700',
                                            'diterima' => 'bg-green-100 text-green-700',
                                        ];
                                        $badgeClass = $statusColors[$order->status_pengiriman] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <tr class="transition hover:bg-green-50/30">
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            {{ $order->buyer->name }}
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-gray-900">
                                            {{ $order->jumlah }} {{ $order->product->satuan }}
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-green-700">
                                            Rp {{ number_format($order->grand_total ?: $order->total_harga, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $badgeClass }}">
                                                {{ ucfirst($order->status_pengiriman) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                            Belum ada pesanan untuk order pool ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>

                @endif


            </div>

        </div>

    </div>

</x-app-layout>