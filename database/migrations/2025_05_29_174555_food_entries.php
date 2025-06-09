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
        Schema::create('food_entries', function (Blueprint $table) {
            $table->id();
            $table->decimal('multiplier', 10, 2)->default(1.00);
            $table->dateTime('ate_at');
            $table->string('description')->nullable();
            $table->decimal('direct_kcal', 10, 2)->nullable();
            $table->decimal('direct_protein', 10, 2)->nullable();
            $table->string('direct_name')->nullable();
            $table->timestamps();
            $table->foreignId('food_item_id')
                ->nullable()
                ->constrained('food_items')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_entries');
    }
};
