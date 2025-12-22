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
        Schema::create('meal_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('food_id')->nullable()->constrained('foods')->onDelete('set null');
            $table->string('food_name'); // Denormalized for historical data
            $table->unsignedBigInteger('fdc_id')->nullable(); // USDA FDC ID (denormalized)
            
            $table->enum('meal_type', ['breakfast', 'lunch', 'dinner', 'snacks']);
            $table->date('date'); // The date the meal was consumed
            $table->decimal('quantity', 8, 2); // Number of servings
            $table->string('serving_size')->default('100g'); // The serving size used
            $table->decimal('serving_weight_grams', 8, 2)->default(100); // Weight in grams for this serving
            
            // Calculated totals (quantity × per_serving values)
            $table->decimal('total_calories', 10, 2);
            $table->decimal('total_protein', 8, 2);
            $table->decimal('total_carbs', 8, 2);
            $table->decimal('total_fats', 8, 2);
            
            $table->timestamps();
            
            $table->index(['user_id', 'date']);
            $table->index(['user_id', 'date', 'meal_type']);
            $table->index('food_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_logs');
    }
};
