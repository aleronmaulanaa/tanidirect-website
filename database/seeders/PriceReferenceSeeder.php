<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PriceReferenceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('price_references')->truncate();

        $this->importBps();
        $this->importSiskaperbapo();
    }

    private function importBps(): void
    {
        $path = storage_path('app/data/harga_pertanian.csv');

        if (!file_exists($path)) {
            $this->command->warn('File harga_pertanian.csv tidak ditemukan.');
            return;
        }

        $handle = fopen($path, 'r');

        $header = fgetcsv($handle);

        $rows = [];

        while (($data = fgetcsv($handle)) !== false) {

            $rows[] = [
                'kategori_komoditas' => trim($data[7]),
                'kabupaten_kota'      => trim($data[5]),
                'sumber'              => 'bps',
                'tipe_harga'          => 'produsen',
                'periode'             => trim($data[6]),
                'harga'               => (float) $data[8],
                'created_at'          => now(),
                'updated_at'          => now(),
            ];

            if (count($rows) >= 500) {
                DB::table('price_references')->insert($rows);
                $rows = [];
            }
        }

        if (!empty($rows)) {
            DB::table('price_references')->insert($rows);
        }

        fclose($handle);
    }

    private function importSiskaperbapo(): void
    {
        $path = storage_path('app/data/harga_konsumen_siskaperbapo.csv');

        if (!file_exists($path)) {
            $this->command->warn('File harga_konsumen_siskaperbapo.csv tidak ditemukan.');
            return;
        }

        $handle = fopen($path, 'r');

        $header = fgetcsv($handle);

        $rows = [];

        while (($data = fgetcsv($handle)) !== false) {

            $rows[] = [
                'kategori_komoditas' => trim($data[2]),
                'kabupaten_kota'      => 'Provinsi Jawa Timur',
                'sumber'              => 'siskaperbapo',
                'tipe_harga'          => 'konsumen',
                'periode'             => trim($data[0]),
                'harga'               => (float) $data[3],
                'created_at'          => now(),
                'updated_at'          => now(),
            ];

            if (count($rows) >= 500) {
                DB::table('price_references')->insert($rows);
                $rows = [];
            }
        }

        if (!empty($rows)) {
            DB::table('price_references')->insert($rows);
        }

        fclose($handle);
    }
}