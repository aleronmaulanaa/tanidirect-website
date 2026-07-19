<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_references', function (Blueprint $table) {
            $table->id();
            $table->string('kategori_komoditas')->index();
            $table->string('kabupaten_kota');
            $table->enum('sumber', ['bps', 'siskaperbapo']);
            $table->enum('tipe_harga', ['produsen', 'konsumen']);
            $table->string('periode', 7); // format YYYY-MM
            $table->decimal('harga', 12, 2);
            $table->timestamps();

            $table->index(['kategori_komoditas', 'periode', 'sumber']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_references');
    }
};