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
        Schema::create('product_buy_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('variant_id')->nullable()->constrained('variants')->onDelete('cascade');
            
            // Platform information
            $table->string('platform'); // shopee, tokopedia, lazada, website, etc.
            $table->string('custom_platform')->nullable(); // if platform is 'other'
            
            // Link details
            $table->string('url');
            $table->decimal('price', 12, 2)->nullable();
            $table->string('affiliate_code')->nullable();
            
            // Status and tracking
            $table->boolean('is_active')->default(true);
            $table->integer('click_count')->default(0);
            $table->integer('order')->default(0);
            
            // Additional data
            $table->json('meta_data')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['product_id', 'is_active']);
            $table->index(['platform', 'is_active']);
            $table->index('url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_buy_links');
    }
};
