<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    /**
     * Menerima input pesan dari pengguna dan mengirimkannya ke Gemini API dengan System Prompt Bertani.
     */
    public function message(Request $request): JsonResponse
    {
        $message = trim((string) $request->input('message', ''));

        if (empty($message)) {
            return response()->json([
                'status' => 'error',
                'reply'  => 'Harap masukkan pertanyaan atau pesan Anda.',
            ], 422);
        }

        // System prompt rahasia untuk membentuk persona 'Bertani'
        $systemPrompt = "Anda adalah 'Bertani', asisten pertanian virtual resmi dari platform TaniDirect. "
            . "Sifat Anda sangat ramah, sopan, komunikatif, dan sangat ahli dalam dunia pertanian Indonesia "
            . "(menguasai komoditas seperti beras, jagung, kedelai, cabai, bawang, teknik bercocok tanam, harga pasar, "
            . "serta fitur TaniDirect seperti jual beli langsung petani-pembeli dan sistem patungan komunal Order Pool). "
            . "Berikan jawaban yang jelas, informatif, membantu, dan santun dalam bahasa Indonesia.";

        $apiKey = config('services.gemini.api_key') ?: env('GEMINI_API_KEY');

        if (! $apiKey) {
            $fallbackReply = $this->getRuleBasedReply($message);
            return response()->json([
                'status' => 'success',
                'reply'  => $fallbackReply,
                'source' => 'rule_based',
            ]);
        }

        try {
            $modelName = config('services.gemini.model') ?: env('GEMINI_MODEL', 'gemini-1.5-flash');
            $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$apiKey}";

            $response = Http::timeout(15)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($endpoint, [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => "{$systemPrompt}\n\nPesan Pengguna: {$message}",
                                ],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 800,
                    ],
                ]);

            if ($response->successful()) {
                $aiReply = data_get($response->json(), 'candidates.0.content.parts.0.text');

                if (! empty($aiReply)) {
                    return response()->json([
                        'status' => 'success',
                        'reply'  => trim($aiReply),
                        'source' => 'gemini',
                    ]);
                }
            }

            // Fallback jika respons API kurang lengkap
            $fallbackReply = $this->getRuleBasedReply($message);

            return response()->json([
                'status' => 'success',
                'reply'  => $fallbackReply,
                'source' => 'fallback',
            ]);

        } catch (\Throwable $e) {
            $fallbackReply = $this->getRuleBasedReply($message);

            return response()->json([
                'status' => 'success',
                'reply'  => $fallbackReply,
                'source' => 'fallback_exception',
            ]);
        }
    }

    /**
     * Alias method `send` untuk rute /chatbot/send.
     */
    public function send(Request $request): JsonResponse
    {
        return $this->message($request);
    }

    /**
     * Logika cadangan berbasis aturan jika API tidak mengembalikan respons.
     */
    protected function getRuleBasedReply(string $message): string
    {
        $normalized = Str::lower($message);

        if (Str::contains($normalized, ['halo', 'hai', 'hello', 'hi'])) {
            return "Halo! Saya Bertani, asisten virtual TaniDirect. Ada yang bisa saya bantu mengenai produk pertanian, harga pasar, atau sistem Order Pool hari ini?";
        }

        if (Str::contains($normalized, ['order pool', 'patungan', 'pool'])) {
            return "Order Pool adalah fitur pembelian komunal di TaniDirect di mana beberapa pembeli bergabung untuk memenuhi kuantitas minimal pesanan agar bisa mendapatkan harga grosir langsung dari petani.";
        }

        if (Str::contains($normalized, ['harga', 'beras', 'jagung', 'cabai'])) {
            return "Anda dapat memantau perkembangan dan referensi harga pasar komoditas pertanian secara real-time pada menu 'Pantau Harga' di TaniDirect.";
        }

        return "Terima kasih atas pertanyaannya! Saya Bertani, asisten pertanian TaniDirect. Saya siap membantu Anda mengenai informasi komoditas pertanian, cara bertransaksi langsung dengan petani, maupun panduan penggunaan platform.";
    }
}
