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
        Schema::create('referral_rewards', function (Blueprint $table) {
    $table->id();
    $table->integer('referral_count'); // contoh: 10, 20, 100
    $table->string('reward_name'); // Gratis Ongkir, Dapat Poin, Elite Member
    $table->string('description')->nullable();
    $table->integer('reward_points')->default(0); // poin bonus
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refferal_rewards');
    }
};
