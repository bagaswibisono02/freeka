<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('item_pesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id');
            $table->foreignId('produk_id');
            $table->integer('jumlah');
            $table->decimal('total_harga', 10, 2);

            // Pengiriman (resi per item)
            $table->string('tracking_number')->nullable();
            $table->string('courier')->nullable();
            $table->json('shipping_data')->nullable(); // <- JSON response API disimpan di sini

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_pesanans');
    }
};
