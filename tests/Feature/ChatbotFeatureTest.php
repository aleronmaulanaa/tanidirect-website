<?php

use App\Models\ProducerProfile;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Http;

it('returns product suggestions for product search requests', function () {
    $user = User::factory()->create([
        'name' => 'Rina',
        'email' => 'rina@example.com',
        'role' => 'pembeli',
    ]);

    $producerProfile = ProducerProfile::create([
        'user_id' => $user->id,
        'kabupaten_kota' => 'Sidoarjo',
        'komoditas_utama' => 'jagung',
        'status_verifikasi' => 'terverifikasi',
    ]);

    Product::create([
        'producer_id' => $producerProfile->id,
        'kategori' => 'jagung',
        'nama_produk' => 'Jagung Manis Premium',
        'harga_jual' => 14000,
        'stok' => 50,
        'satuan' => 'kg',
        'deskripsi' => 'Jagung kualitas premium untuk kebutuhan kuliner.',
        'is_active' => true,
    ]);

    $response = $this->postJson('/chatbot/message', [
        'message' => 'Ada jagung?',
    ]);

    $response->assertOk();
    $response->assertJsonPath('type', 'products');
    $response->assertJsonFragment(['nama_produk' => 'Jagung Manis Premium']);
});

it('renders a csrf token meta tag on the landing page', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('name="csrf-token"', false);
});

it('uses an api response when the ai provider is configured', function () {
    Http::fake([
        'https://generativelanguage.googleapis.com/v1beta/models/*' => Http::response([
            'candidates' => [
                [
                    'content' => [
                        'parts' => [
                            [
                                'text' => 'Saya bisa bantu dengan produk pertanian yang Anda cari.',
                            ],
                        ],
                    ],
                ],
            ],
        ], 200),
    ]);

    config()->set('services.gemini.api_key', 'test-key');

    $response = $this->postJson('/chatbot/message', [
        'message' => 'Aku mau tahu tentang beras premium',
    ]);

    $response->assertOk();
    $response->assertJsonPath('type', 'ai');
    $response->assertJsonFragment(['reply' => 'Saya bisa bantu dengan produk pertanian yang Anda cari.']);
});
