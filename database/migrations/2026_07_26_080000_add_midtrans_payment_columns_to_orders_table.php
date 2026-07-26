<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('grand_total');
            $table->string('payment_status')->nullable()->after('payment_method');
            $table->string('midtrans_order_id')->nullable()->after('payment_status')->unique();
            $table->string('midtrans_transaction_id')->nullable()->after('midtrans_order_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['midtrans_order_id']);
            $table->dropColumn([
                'payment_method',
                'payment_status',
                'midtrans_order_id',
                'midtrans_transaction_id',
            ]);
        });
    }
};
