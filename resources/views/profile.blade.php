<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    {{ __('Profil Saya') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Kelola informasi akun Anda sesuai peran di TaniDirect.
                </p>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-2xl bg-green-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-green-800">
                Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <section class="overflow-hidden rounded-[2rem] bg-gradient-to-br from-green-800 via-green-700 to-emerald-600 p-7 text-white shadow-xl sm:p-8">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-2xl">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-green-100">Profil {{ $user->role === 'produsen' ? 'Penjual' : 'Pembeli' }}</p>
                        <h3 class="mt-3 text-3xl font-extrabold sm:text-4xl">{{ $user->name }}</h3>
                        <p class="mt-3 text-sm leading-7 text-green-50 sm:text-base">
                            Lengkapi informasi akun Anda agar transaksi, komunikasi, dan interaksi dengan mitra jadi lebih aman dan nyaman.
                        </p>
                    </div>
                    <div class="rounded-2xl border border-white/20 bg-white/15 px-4 py-3 text-sm backdrop-blur">
                        <p class="font-semibold text-green-50">Status akun</p>
                        <p class="mt-1 text-base font-bold capitalize">{{ $user->role }}</p>
                    </div>
                </div>
            </section>

            <div class="grid gap-6 lg:grid-cols-[1.25fr_0.75fr]">
                <div class="rounded-[2rem] border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Informasi Pribadi</h3>
                            <p class="mt-1 text-sm text-gray-500">Data ini diambil langsung dari akun yang sedang login.</p>
                        </div>
                        <div class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-green-700">
                            Aktif
                        </div>
                    </div>

                    <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                        @csrf
                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label for="name" class="mb-2 block text-sm font-semibold text-gray-700">Nama Lengkap</label>
                                <input id="name" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 transition focus:border-green-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-200">
                                @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="phone" class="mb-2 block text-sm font-semibold text-gray-700">Nomor HP</label>
                                <input id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 transition focus:border-green-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-200" placeholder="Contoh: 081234567890">
                                @error('phone') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="kabupaten_kota" class="mb-2 block text-sm font-semibold text-gray-700">Kabupaten / Kota</label>
                                <input id="kabupaten_kota" name="kabupaten_kota" value="{{ old('kabupaten_kota', $user->kabupaten_kota) }}" class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 transition focus:border-green-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-200" placeholder="Contoh: Surabaya">
                                @error('kabupaten_kota') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            @if ($user->role === 'produsen')
                                <div>
                                    <label for="lokasi_desa" class="mb-2 block text-sm font-semibold text-gray-700">Lokasi Desa</label>
                                    <input id="lokasi_desa" name="lokasi_desa" value="{{ old('lokasi_desa', $user->producerProfile?->lokasi_desa) }}" class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 transition focus:border-green-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-200" placeholder="Contoh: Tegalrejo">
                                    @error('lokasi_desa') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="komoditas_utama" class="mb-2 block text-sm font-semibold text-gray-700">Komoditas Utama</label>
                                    <input id="komoditas_utama" name="komoditas_utama" value="{{ old('komoditas_utama', $user->producerProfile?->komoditas_utama) }}" class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 transition focus:border-green-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-200" placeholder="Contoh: Beras">
                                    @error('komoditas_utama') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center gap-3 border-t border-gray-100 pt-4">
                            <button type="submit" class="rounded-2xl bg-green-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-green-800">
                                Simpan Profil
                            </button>
                            <a href="{{ route('dashboard') }}" class="rounded-2xl border border-gray-200 px-5 py-3 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>

                <div class="space-y-6">
                    <div class="rounded-[2rem] border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
                        <h3 class="text-lg font-bold text-gray-900">Informasi Akun</h3>
                        <div class="mt-4 space-y-3 text-sm text-gray-600">
                            <div class="flex items-center justify-between rounded-2xl bg-gray-50 px-4 py-3">
                                <span>Email</span>
                                <span class="font-semibold text-gray-900">{{ $user->email }}</span>
                            </div>
                            <div class="flex items-center justify-between rounded-2xl bg-gray-50 px-4 py-3">
                                <span>Role</span>
                                <span class="font-semibold capitalize text-gray-900">{{ $user->role }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[2rem] border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
                        <h3 class="text-lg font-bold text-gray-900">Keamanan</h3>
                        <p class="mt-3 text-sm leading-6 text-gray-500">
                            Ubah kata sandi Anda dengan aman melalui form pengaturan akun di bawah ini.
                        </p>
                        <div class="mt-5">
                            <livewire:profile.update-password-form />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
