<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producer_id')->constrained('producer_profiles')->cascadeOnDelete();
            $table->enum('kategori', ['beras_medium', 'beras_premium', 'jagung'])->index();
            $table->string('nama_produk');
            $table->decimal('harga_jual', 12, 2);
            $table->unsignedInteger('stok')->default(0);
            $table->string('satuan')->default('kg');
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};