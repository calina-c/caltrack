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
        Schema::create('goal_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('target_qty')->default(0);
            $table->boolean('is_current')->default(true);
            $table->timestamps();
        });

        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index();
            $table->integer('qty')->default(0);
            $table->foreignId('goal_type_id')->constrained('goal_types')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goals');
        Schema::dropIfExists('goal_types');
    }
};
