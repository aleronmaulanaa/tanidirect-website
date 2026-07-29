<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen & Verifikasi Produsen | TaniDirect Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f8f3] text-gray-900">

    {{-- Navbar Admin --}}
    <header class="sticky top-0 z-50 border-b border-green-100 bg-white/95 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 sm:px-8">
            <div class="flex items-center gap-3">
                <a href="{{ route('landing') }}"><img src="{{ asset('images/logo.png') }}" alt="TaniDirect" class="h-9 w-auto"></a>
                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-extrabold tracking-wider text-emerald-800 uppercase">
                    ADMINISTRATOR
                </span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    Dashboard
                </a>
                <a href="{{ route('admin.producers.index') }}" class="rounded-xl border border-green-200 bg-green-50 px-4 py-2 text-sm font-semibold text-green-700 transition">
                    Manajemen Produsen
                </a>
                <a href="{{ route('profile') }}" class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    Profil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded-xl px-4 py-2 text-sm font-semibold text-gray-600 transition hover:bg-red-50 hover:text-red-600">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-5 py-10 sm:px-8">

        {{-- Page Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Manajemen & Verifikasi Produsen</h1>
                <p class="mt-2 text-sm text-gray-500">Kelola akun petani dan lakukan verifikasi keabsahan profil produsen.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}"
               class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                ← Kembali ke Dashboard
            </a>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mt-6 flex items-center justify-between rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-800 shadow-sm">
                <div class="flex items-center gap-3">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-green-200 text-sm font-bold">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-800 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Tabel Data Produsen --}}
        <section class="mt-8">
            <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex items-center justify-between border-b border-gray-100 pb-5">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Daftar Akun Produsen (Petani)</h2>
                        <p class="text-sm text-gray-500">Total {{ number_format($producers->total()) }} akun produsen terdaftar di platform.</p>
                    </div>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50/80">
                                <th class="px-6 py-4 font-bold text-gray-700">Nama & Profil</th>
                                <th class="px-6 py-4 font-bold text-gray-700">Email</th>
                                <th class="px-6 py-4 font-bold text-gray-700">Tanggal Daftar</th>
                                <th class="px-6 py-4 font-bold text-gray-700 text-center">Status Verifikasi</th>
                                <th class="px-6 py-4 font-bold text-gray-700 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($producers as $producer)
                                @php
                                    $profile = $producer->producerProfile;
                                    $status = $profile->status_verifikasi ?? 'menunggu';
                                @endphp
                                <tr class="transition hover:bg-green-50/30">

                                    {{-- Nama & Profil --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 font-extrabold text-emerald-800">
                                                {{ strtoupper(substr($producer->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900">{{ $producer->name }}</div>
                                                <div class="text-xs text-gray-500">
                                                    📍 {{ $producer->kabupaten_kota ?: ($profile->kabupaten_kota ?? '-') }}
                                                    @if($profile && $profile->komoditas_utama && $profile->komoditas_utama !== '-')
                                                        · <span class="font-semibold text-green-700">🌾 {{ $profile->komoditas_utama }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Email --}}
                                    <td class="px-6 py-4 font-medium text-gray-700">
                                        {{ $producer->email }}
                                    </td>

                                    {{-- Tanggal Daftar --}}
                                    <td class="px-6 py-4 text-gray-500">
                                        {{ $producer->created_at->format('d M Y, H:i') }}
                                    </td>

                                    {{-- Status Verifikasi --}}
                                    <td class="px-6 py-4 text-center">
                                        @if($status === 'terverifikasi')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-3.5 py-1 text-xs font-bold text-green-800 ring-1 ring-green-600/20">
                                                <span class="h-2 w-2 rounded-full bg-green-600"></span>
                                                Terverifikasi
                                            </span>
                                        @elseif($status === 'ditolak')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-3.5 py-1 text-xs font-bold text-red-800 ring-1 ring-red-600/20">
                                                <span class="h-2 w-2 rounded-full bg-red-600"></span>
                                                Ditolak
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3.5 py-1 text-xs font-bold text-amber-800 ring-1 ring-amber-600/20">
                                                <span class="h-2 w-2 rounded-full bg-amber-600 animate-pulse"></span>
                                                Menunggu Verifikasi
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-6 py-4 text-center">
                                        @if($status !== 'terverifikasi')
                                            <form method="POST" action="{{ route('admin.producers.verify', $producer) }}" class="inline-block">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-xl bg-green-700 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-green-800 active:scale-95"
                                                    onclick="return confirm('Apakah Anda yakin ingin memverifikasi produsen {{ $producer->name }}?')"
                                                >
                                                    ✅ Verifikasi
                                                </button>
                                            </form>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-xs font-bold text-green-700">
                                                ✓ Verified
                                            </span>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                        Belum ada akun produsen yang terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                @if($producers->hasPages())
                    <div class="mt-6 border-t border-gray-100 pt-4">
                        {{ $producers->links() }}
                    </div>
                @endif
            </div>
        </section>

    </main>

</body>
</html>
