<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProducerProductController extends Controller
{
    public function index(Request $request): View
    {
        $profile = $this->producerProfile($request);

        return view('producer.products.index', [
            'products' => $profile->products()
                ->withCount(['orders', 'orderPools'])
                ->latest()
                ->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('producer.products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $product = new Product($this->validatedProduct($request));
        $product->producer_id = $this->producerProfile($request)->id;

        if ($request->hasFile('gambar')) {
            $product->gambar = $request->file('gambar')->store('products', 'public');
        }

        $product->save();

        return redirect()->route('producer.products.index')
            ->with('success', 'Produk berhasil ditambahkan dan siap ditampilkan ke pembeli.');
    }

    public function edit(Request $request, Product $product): View
    {
        $this->ensureOwnership($request, $product);

        return view('producer.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->ensureOwnership($request, $product);

        $data = $this->validatedProduct($request);

        if ($request->hasFile('gambar')) {
            if ($product->gambar) {
                Storage::disk('public')->delete($product->gambar);
            }

            $data['gambar'] = $request->file('gambar')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('producer.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function toggleStatus(Request $request, Product $product): RedirectResponse
    {
        $this->ensureOwnership($request, $product);

        $product->update(['is_active' => ! $product->is_active]);

        return back()->with(
            'success',
            $product->is_active ? 'Produk diaktifkan.' : 'Produk dinonaktifkan.'
        );
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $this->ensureOwnership($request, $product);

        if ($product->orders()->exists() || $product->orderPools()->exists()) {
            return back()->with(
                'error',
                'Produk yang sudah memiliki pesanan atau group buy tidak dapat dihapus. Nonaktifkan produk saja.'
            );
        }

        if ($product->gambar) {
            Storage::disk('public')->delete($product->gambar);
        }

        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }

    private function producerProfile(Request $request)
    {
        return $request->user()->producerProfile
            ?? abort(403, 'Profil produsen belum tersedia.');
    }

    private function ensureOwnership(Request $request, Product $product): void
    {
        abort_unless(
            $product->producer_id === $this->producerProfile($request)->id,
            403
        );
    }

    private function validatedProduct(Request $request): array
    {
        $data = $request->validate([
            'nama_produk' => ['required', 'string', 'max:120'],
            'kategori' => ['required', 'in:beras_medium,beras_premium,jagung'],
            'harga_jual' => ['required', 'numeric', 'min:1'],
            'stok' => ['required', 'integer', 'min:0'],
            'satuan' => ['required', 'in:kg'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
