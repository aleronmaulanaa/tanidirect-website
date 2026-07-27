<?php

namespace App\Http\Controllers;

use App\Models\OrderPool;
use App\Models\PriceReference;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    public function message(Request $request): JsonResponse
    {
        $message = trim((string) $request->input('message', ''));
        $normalized = Str::lower($message);

        $response = $this->buildResponse($normalized, $message);

        return response()->json([
            'reply' => $response['reply'],
            'type' => $response['type'],
            'data' => $response['data'] ?? [],
        ]);
    }

    protected function buildResponse(string $normalized, string $original): array
    {
        if ($this->shouldUseAi($normalized, $original)) {
            $aiReply = $this->generateAiReply($original);

            if ($aiReply) {
                return [
                    'type' => 'ai',
                    'reply' => $aiReply,
                ];
            }
        }

        if ($this->containsAny($normalized, ['halo', 'hai', 'hello', 'hallo', 'hi'])) {
            return [
                'type' => 'greeting',
                'reply' => "Halo! Saya Bertani, asisten digital TaniDirect. Saya bisa membantu Anda mencari produk, melihat harga pasar, memahami Order Pool, dan menjelaskan cara memakai platform.\n\nCoba kirim pertanyaan seperti: \n- Ada jagung?\n- Cek harga beras\n- Apa itu Order Pool?",
            ];
        }

        if ($this->containsAny($normalized, ['apa itu tanidirect', 'apa itu tani direct', 'tani direct'])) {
            return [
                'type' => 'general',
                'reply' => "TaniDirect adalah platform yang menghubungkan petani dan pembeli secara langsung. Di sini Anda bisa menemukan produk pertanian, melihat harga yang lebih transparan, dan ikut sistem pembelian komunal melalui Order Pool.",
            ];
        }

        if ($this->containsAny($normalized, ['order pool', 'patungan', 'gabung', 'pool'])) {
            return $this->buildOrderPoolResponse();
        }

        if ($this->containsAny($normalized, ['harga', 'cek harga', 'harga pasar', 'mahal'])) {
            return $this->buildPriceResponse($normalized);
        }

        if ($this->containsAny($normalized, ['produk', 'cari', 'beli', 'ingin membeli', 'mau beli', 'ada'])) {
            return $this->buildProductResponse($original);
        }

        if ($this->containsAny($normalized, ['petani', 'dashboard', 'cara menambah', 'cara mengubah', 'stok', 'pesanan'])) {
            return [
                'type' => 'help',
                'reply' => "Untuk petani, Anda dapat menambah produk di dashboard, mengubah stok, melihat pesanan, dan memantau Order Pool yang aktif. Jika Anda ingin, saya bisa bantu memberi panduan langkah demi langkah sesuai kebutuhan Anda.",
            ];
        }

        return [
            'type' => 'fallback',
            'reply' => "Saya masih belajar memahami pertanyaan Anda. Anda bisa mencoba salah satu opsi berikut:\n- Cari produk pertanian\n- Cek harga pasar\n- Pelajari Order Pool\n- Bantuan TaniDirect",
        ];
    }

    protected function buildProductResponse(string $message): array
    {
        $keywords = preg_split('/\s+/', preg_replace('/[^a-z0-9]+/i', ' ', $message));
        $keywords = array_values(array_unique(array_filter($keywords, fn ($keyword) => mb_strlen($keyword) >= 2)));

        $query = Product::query()
            ->where('is_active', true)
            ->with('producer');

        foreach ($keywords as $keyword) {
            if (in_array(Str::lower($keyword), ['ada', 'ingin', 'mau', 'beli', 'cari', 'produk', 'saya', 'aku', 'yang'])) {
                continue;
            }

            $query->where(function ($subQuery) use ($keyword): void {
                $subQuery->where('nama_produk', 'like', "%{$keyword}%")
                    ->orWhere('deskripsi', 'like', "%{$keyword}%")
                    ->orWhere('kategori', 'like', "%{$keyword}%")
                    ->orWhere('satuan', 'like', "%{$keyword}%");
            });
        }

        $products = $query->latest()->limit(3)->get();

        if ($products->isEmpty()) {
            return [
                'type' => 'products',
                'reply' => "Saya belum menemukan produk yang sesuai dengan pencarian Anda. Anda bisa mencoba kata kunci seperti 'beras', 'jagung', atau 'premium'.",
                'data' => [],
            ];
        }

        $lines = $products->map(function (Product $product): string {
            $producerName = $product->producer?->kabupaten_kota ? " - {$product->producer->kabupaten_kota}" : '';

            return "• {$product->nama_produk} ({$product->kategori})\n  Harga: Rp" . number_format((float) $product->harga_jual, 0, ',', '.') . "/{$product->satuan}\n  Stok: {$product->stok} {$product->satuan}{$producerName}";
        })->implode("\n\n");

        return [
            'type' => 'products',
            'reply' => "Saya menemukan beberapa produk yang mungkin sesuai:\n\n{$lines}",
            'data' => $products->map(fn (Product $product) => [
                'nama_produk' => $product->nama_produk,
                'harga_jual' => (float) $product->harga_jual,
                'stok' => $product->stok,
                'kategori' => $product->kategori,
                'lokasi' => $product->producer?->kabupaten_kota,
            ])->values()->all(),
        ];
    }

    protected function buildPriceResponse(string $normalized): array
    {
        $commodity = null;

        foreach (['beras', 'jagung', 'kedelai', 'cabai', 'tomat'] as $candidate) {
            if (Str::contains($normalized, $candidate)) {
                $commodity = $candidate;
                break;
            }
        }

        $query = PriceReference::query()
            ->where('harga', '>', 0)
            ->latest('periode');

        if ($commodity) {
            $query->whereRaw('LOWER(kategori_komoditas) LIKE ?', ['%' . $commodity . '%']);
        }

        $references = $query->limit(5)->get();

        if ($references->isEmpty()) {
            return [
                'type' => 'price',
                'reply' => "Belum ada referensi harga yang tersedia untuk komoditas tersebut saat ini.",
            ];
        }

        $lines = $references->map(function (PriceReference $reference): string {
            return "• {$reference->kategori_komoditas} ({$reference->periode})\n  {$reference->tipe_harga}: Rp" . number_format((float) $reference->harga, 0, ',', '.') . "\n  Sumber: {$reference->sumber}";
        })->implode("\n\n");

        return [
            'type' => 'price',
            'reply' => "Berikut referensi harga yang saya temukan:\n\n{$lines}",
            'data' => $references->map(fn (PriceReference $reference) => [
                'kategori_komoditas' => $reference->kategori_komoditas,
                'harga' => (float) $reference->harga,
                'tipe_harga' => $reference->tipe_harga,
                'periode' => $reference->periode,
            ])->values()->all(),
        ];
    }

    protected function buildOrderPoolResponse(): array
    {
        $orderPools = OrderPool::query()
            ->with('product')
            ->where('status', 'open')
            ->latest()
            ->limit(3)
            ->get();

        if ($orderPools->isEmpty()) {
            return [
                'type' => 'order_pool',
                'reply' => "Saat ini belum ada Order Pool yang aktif. Anda bisa mulai membuat patungan untuk produk yang Anda inginkan.",
            ];
        }

        $lines = $orderPools->map(function (OrderPool $orderPool): string {
            $productName = $orderPool->product?->nama_produk ?? 'Produk';
            $target = $orderPool->target_volume;
            $collected = $orderPool->volume_terkumpul;

            return "• {$productName}\n  Target: {$target} kg\n  Terkumpul: {$collected} kg\n  Status: {$orderPool->status}";
        })->implode("\n\n");

        return [
            'type' => 'order_pool',
            'reply' => "Order Pool yang sedang aktif:\n\n{$lines}\n\nOrder Pool adalah sistem pembelian bersama agar target volume terpenuhi dan pesanan bisa diproses lebih cepat.",
        ];
    }

    protected function shouldUseAi(string $normalized, string $original): bool
    {
        $apiKey = config('services.gemini.api_key');

        if (empty($apiKey)) {
            return false;
        }

        return $this->containsAny($normalized, ['produk', 'beras', 'jagung', 'harga', 'order pool', 'petani', 'beli', 'tani', 'apa itu']) || mb_strlen(trim($original)) > 8;
    }

    protected function generateAiReply(string $message): ?string
    {
        $apiKey = config('services.gemini.api_key');

        if (empty($apiKey)) {
            return null;
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://generativelanguage.googleapis.com/v1beta/models/' . config('services.gemini.model', 'gemini-2.0-flash') . ':generateContent?key=' . $apiKey, [
                    'contents' => [[
                        'parts' => [[
                            'text' => "Anda adalah Bertani, asisten digital TaniDirect untuk pertanian Indonesia. Jawab singkat, ramah, dan relevan dengan produk pertanian, harga, Order Pool, dan platform TaniDirect.\n\nPertanyaan pengguna: {$message}",
                        ]],
                    ]],
                    'generationConfig' => [
                        'temperature' => 0.7,
                    ],
                ]);

            if ($response->failed()) {
                return null;
            }

            $content = data_get($response->json(), 'candidates.0.content.parts.0.text');

            return is_string($content) && trim($content) !== '' ? trim($content) : null;
        } catch (\Throwable $exception) {
            return null;
        }
    }

    protected function containsAny(string $haystack, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (Str::contains($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }
}
