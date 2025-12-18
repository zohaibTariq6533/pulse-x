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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('gender',['male','female'])->nullable();
            $table->integer('age')->nullable();
            $table->decimal('height')->nullable();
            $table->decimal('weight')->nullable();
            $table->enum('activity_level',['basic','moderate','high'])->nullable();
            $table->enum('goal',['weight_lose','weight_gain','weight_maintain','cut','bulk'])->nullable();
            $table->boolean('goes_to_gym')->default(false);
            $table->string('gym_experience')->nullable();
            $table->integer('current_calories')->default(0);
            $table->integer('goal_calories')->default(0);
            $table->integer('goal_protein')->default(0);
            $table->integer('goal_carbs')->default(0);
            $table->integer('goal_fat')->default(0);
            $table->integer('bmr')->default(0);
            $table->integer('calories_gap')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
