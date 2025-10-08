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
        Schema::create('variants', function (Blueprint $table) {
           $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Variant Identification
            $table->string('name');
            $table->string('type'); // color, size, material, edition, custom
            $table->string('value'); // Red, XL, Leather, Premium, etc.
            $table->string('sku')->unique()->nullable();
            
            // Pricing & Inventory
            $table->decimal('price', 12, 2);
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            
            // Media for this specific variant
            $table->string('image')->nullable();
            $table->string('video')->nullable();
            $table->text('file_path')->nullable()->comment('For additional files');
            
            // Additional variant data (JSON for flexibility)
            $table->json('additional_data')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['product_id', 'is_active']);
            $table->index('sku');
            $table->index(['type', 'value']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
};
