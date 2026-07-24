@php
    $isEditing = isset($product);
    $value = fn (string $field, mixed $default = null) => old($field, $isEditing ? $product->{$field} : $default);
@endphp

<input type="hidden" name="satuan" value="kg">

@if($errors->any())
    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
        <p class="font-bold">Produk belum dapat disimpan.</p>
        <ul class="mt-2 list-inside list-disc space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="nama_produk" class="mb-2 block text-sm font-semibold text-gray-700">Nama produk</label>
        <input id="nama_produk" name="nama_produk" type="text" value="{{ $value('nama_produk') }}" required maxlength="120"
            class="w-full rounded-xl border-gray-300 focus:border-green-600 focus:ring-green-600" placeholder="Contoh: Beras Pandan Wangi">
        @error('nama_produk') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="kategori" class="mb-2 block text-sm font-semibold text-gray-700">Kategori</label>
        <select id="kategori" name="kategori" required class="w-full rounded-xl border-gray-300 focus:border-green-600 focus:ring-green-600">
            <option value="">Pilih kategori</option>
            <option value="beras_medium" @selected($value('kategori') === 'beras_medium')>Beras Medium</option>
            <option value="beras_premium" @selected($value('kategori') === 'beras_premium')>Beras Premium</option>
            <option value="jagung" @selected($value('kategori') === 'jagung')>Jagung</option>
        </select>
        @error('kategori') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="harga_jual" class="mb-2 block text-sm font-semibold text-gray-700">Harga per kg (Rp)</label>
        <input id="harga_jual" name="harga_jual" type="number" value="{{ $value('harga_jual') }}" required min="1" step="1"
            class="w-full rounded-xl border-gray-300 focus:border-green-600 focus:ring-green-600" placeholder="12000">
        @error('harga_jual') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="stok" class="mb-2 block text-sm font-semibold text-gray-700">Stok tersedia (kg)</label>
        <input id="stok" name="stok" type="number" value="{{ $value('stok', 0) }}" required min="0" step="1"
            class="w-full rounded-xl border-gray-300 focus:border-green-600 focus:ring-green-600" placeholder="100">
        @error('stok') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="gambar" class="mb-2 block text-sm font-semibold text-gray-700">Foto produk</label>
        <input id="gambar" name="gambar" type="file" accept="image/jpeg,image/png,image/webp"
            class="block w-full rounded-xl border border-gray-300 text-sm text-gray-600 file:mr-4 file:border-0 file:bg-green-50 file:px-4 file:py-2 file:font-semibold file:text-green-700 hover:file:bg-green-100">
        <p class="mt-1 text-xs text-gray-500">JPG, PNG, atau WEBP. Maksimal 2 MB.</p>
        @error('gambar') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-end">
        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 px-4 py-3 text-sm font-semibold text-gray-700">
            <input type="checkbox" name="is_active" value="1" @checked($value('is_active', true))
                class="rounded border-gray-300 text-green-700 focus:ring-green-600">
            Tampilkan produk untuk pembeli
        </label>
    </div>
</div>

<div class="mt-6">
    <label for="deskripsi" class="mb-2 block text-sm font-semibold text-gray-700">Deskripsi produk <span class="font-normal text-gray-400">(opsional)</span></label>
    <textarea id="deskripsi" name="deskripsi" rows="5" maxlength="2000"
        class="w-full rounded-xl border-gray-300 focus:border-green-600 focus:ring-green-600" placeholder="Jelaskan kualitas, asal panen, atau informasi penting produk.">{{ $value('deskripsi') }}</textarea>
    @error('deskripsi') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

@if($isEditing && $product->gambar)
    <div class="mt-6">
        <p class="mb-2 text-sm font-semibold text-gray-700">Foto saat ini</p>
        <img src="{{ asset('storage/'.$product->gambar) }}" alt="{{ $product->nama_produk }}" class="h-32 w-48 rounded-xl object-cover">
    </div>
@endif

<div class="mt-8 flex flex-wrap justify-end gap-3 border-t border-gray-100 pt-6">
    <a href="{{ route('producer.products.index') }}" class="rounded-xl px-5 py-3 text-sm font-semibold text-gray-600 hover:bg-gray-100">Batal</a>
    <button type="submit" class="rounded-xl bg-green-700 px-6 py-3 text-sm font-semibold text-white transition hover:bg-green-800">
        {{ $isEditing ? 'Simpan Perubahan' : 'Simpan Produk' }}
    </button>
</div>
