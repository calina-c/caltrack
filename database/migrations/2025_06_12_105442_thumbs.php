<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('thumbs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_item_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('food_entry_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('type', ['up', 'down']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thumbs');
    }
};
