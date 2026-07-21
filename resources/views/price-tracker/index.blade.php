<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    📈 Price Transparency Tracker
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Bandingkan harga produsen (BPS) dengan harga konsumen
                    (Siskaperbapo) secara transparan.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Filter --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Periode
                        </label>

                        <select
                            name="periode"
                            onchange="this.form.submit()"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"
                        >
                            @foreach($periodes as $item)
                                <option
                                    value="{{ $item }}"
                                    @selected($item == $periode)
                                >
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Komoditas
                        </label>

                        <select
                            name="komoditas"
                            onchange="this.form.submit()"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"
                        >
                            <option value="">
                                Semua Komoditas
                            </option>

                            @foreach($komoditasList as $item)
                                <option
                                    value="{{ $item }}"
                                    @selected($item == $komoditas)
                                >
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button
                            type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white rounded-lg px-4 py-2 font-medium transition"
                        >
                            Terapkan Filter
                        </button>
                    </div>

                </form>
            </div>

            @php
                $avgProdusen = collect($tracker)->avg('harga_produsen');

                $avgKonsumen = collect($tracker)
                    ->whereNotNull('harga_konsumen')
                    ->avg('harga_konsumen');

                $avgMargin = collect($tracker)
                    ->whereNotNull('margin')
                    ->avg('margin');
            @endphp

            {{-- Summary Card --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">

                <div class="bg-white rounded-xl shadow-sm p-5">
                    <p class="text-sm text-gray-500">
                        Rata-rata Harga Produsen
                    </p>

                    <h3 class="mt-2 text-2xl font-bold text-green-700">
                        Rp {{ number_format($avgProdusen,0,',','.') }}
                    </h3>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-5">
                    <p class="text-sm text-gray-500">
                        Rata-rata Harga Konsumen
                    </p>

                    <h3 class="mt-2 text-2xl font-bold text-blue-700">
                        @if(is_null($avgKonsumen))
                            <span class="text-gray-500 text-lg">
                                Data tidak tersedia
                            </span>
                        @else
                            Rp {{ number_format($avgKonsumen,0,',','.') }}
                        @endif
                    </h3>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-5">
                    <p class="text-sm text-gray-500">
                        Rata-rata Margin
                    </p>

                    <h3 class="mt-2 text-2xl font-bold text-red-600">
                        @if(is_null($avgMargin))
                            <span class="text-gray-500 text-lg">
                                Data tidak tersedia
                            </span>
                        @else
                            Rp {{ number_format($avgMargin,0,',','.') }}
                        @endif
                    </h3>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-5">
                    <p class="text-sm text-gray-500">
                        Total Komoditas
                    </p>

                    <h3 class="mt-2 text-2xl font-bold text-gray-800">
                        {{ count($tracker) }}
                    </h3>
                </div>

            </div>
                        {{-- Data Table --}}
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Data Perbandingan Harga
                    </h3>
                    <p class="text-sm text-gray-500">
                        Perbandingan harga produsen dan harga konsumen berdasarkan periode yang dipilih.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Komoditas
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Harga Produsen
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Harga Konsumen
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Margin
                                </th>

                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Persentase
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">

                            @forelse($tracker as $item)

                                @php
                                    $badge = 'bg-green-100 text-green-700';

                                    if ($item['persentase_margin'] > 40) {
                                        $badge = 'bg-red-100 text-red-700';
                                    } elseif ($item['persentase_margin'] > 20) {
                                        $badge = 'bg-yellow-100 text-yellow-700';
                                    }
                                @endphp

                                <tr class="hover:bg-gray-50 transition">

                                    <td class="px-6 py-4 font-medium text-gray-800">
                                        {{ $item['kategori_komoditas'] }}
                                    </td>

                                    <td class="px-6 py-4 text-right text-green-700 font-semibold">
                                        Rp {{ number_format($item['harga_produsen'],0,',','.') }}
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        @if(is_null($item['harga_konsumen']))
                                            <span class="text-gray-500 italic">
                                                Data konsumen tidak tersedia
                                            </span>
                                        @else
                                            <span class="text-blue-700 font-semibold">
                                                Rp {{ number_format($item['harga_konsumen'],0,',','.') }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-right text-red-600 font-semibold">
                                        Rp {{ number_format($item['margin'],0,',','.') }}
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                            {{ number_format($item['persentase_margin'],1) }}%
                                        </span>
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5" class="py-12 text-center text-gray-500">
                                        Tidak ada data yang ditemukan.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>