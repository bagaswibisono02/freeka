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
        Schema::create('product_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('variant_id')->nullable()->constrained('variants')->onDelete('cascade');
            
            // Media details
            $table->string('file_path');
            $table->string('file_type'); // image, video
            $table->string('mime_type');
            $table->string('file_name');
            $table->integer('file_size')->nullable();
            $table->text('alt_text')->nullable();
            $table->text('caption')->nullable();
            
            // Order and display
            $table->integer('order')->default(0);
            $table->boolean('is_main')->default(false);
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['product_id', 'is_active']);
            $table->index(['variant_id', 'is_active']);
            $table->index('file_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_media');
    }
};
