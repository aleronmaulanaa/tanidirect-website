<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\OrderPool;
use App\Models\ProducerProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProducerSeeder extends Seeder
{
    public function run(): void
    {
        $producer = User::updateOrCreate(
            [
                'email' => 'produsen@tanidirect.com',
            ],
            [
                'name' => 'Pak Budi',
                'password' => Hash::make('password'),
                'role' => 'produsen',
                'phone' => '081234567890',
                'kabupaten_kota' => 'Kabupaten Malang',
            ]
        );

        ProducerProfile::updateOrCreate(
            [
                'user_id' => $producer->id,
            ],
            [
                'lokasi_desa' => 'Desa Sumberrejo',
                'kabupaten_kota' => 'Kabupaten Malang',
                'komoditas_utama' => 'Beras',
                'status_verifikasi' => 'terverifikasi',
            ]
        );

        // $product = Product::create([
        //     'producer_id' => $profile->id,
        //     'kategori' => 'beras_premium',
        //     'nama_produk' => 'Beras Premium',
        //     'harga_jual' => 12000,
        //     'stok' => 500,
        //     'satuan' => 'kg',
        //     'deskripsi' => 'Beras premium langsung dari petani Kabupaten Malang.',
        //     'is_active' => true,
        // ]);

        // OrderPool::create([
        //     'product_id' => $product->id,
        //     'target_volume' => 100,
        //     'volume_terkumpul' => 0,
        //     'status' => 'open',
        //     'batas_waktu' => now()->addDays(10),
        // ]);
    }
}