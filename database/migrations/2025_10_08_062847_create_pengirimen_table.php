<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengirimen', function (Blueprint $table) {
           
            // Relasi ke produk (atau bisa ke pesanan tergantung kebutuhan)
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            $table->string('expedisi')->nullable();       // nama jasa ekspedisi
            $table->string('nomer_resi')->nullable(); // nomor resi
            $table->json('cek_resi')->nullable();     // data json dari API cek resi

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengirimen');
    }
};
