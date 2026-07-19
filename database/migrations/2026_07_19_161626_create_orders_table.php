<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('order_pool_id')->nullable()->constrained('order_pools')->nullOnDelete();
            $table->unsignedInteger('jumlah');
            $table->decimal('total_harga', 14, 2);
            $table->enum('status_pengiriman', ['dipesan', 'diproses', 'dikirim', 'diterima'])->default('dipesan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};