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
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fdc_id')->unique()->index(); // USDA FDC ID
            $table->string('name'); // description field from USDA 
            $table->string('serving_size')->default('100g'); // Standard serving (USDA uses 100g base)
            $table->decimal('serving_weight_grams', 8, 2)->default(100); // Weight in grams
            
            // Nutrition per serving (normalized to serving_size)
            $table->decimal('calories_per_serving', 8, 2);
            $table->decimal('protein_per_serving', 8, 2)->default(0); // grams
            $table->decimal('carbs_per_serving', 8, 2)->default(0); // grams
            $table->decimal('fats_per_serving', 8, 2)->default(0); // grams
            
            
            $table->string('api_source')->default('usda'); // 'usda' or 'custom'
            $table->boolean('is_custom')->default(false);
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->integer('times_logged')->default(0); // Track popularity
            $table->timestamp('cached_at')->nullable(); // When cached from API
            
            $table->timestamps();
            
            $table->index('name');
            $table->index(['is_custom', 'created_by_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foods');
    }
};
