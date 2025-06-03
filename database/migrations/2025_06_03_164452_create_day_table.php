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
        Schema::create('days', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique()->comment('The date of the day');
            $table->boolean('done')->default(false)->comment('Indicates if the day is completed');
            $table->integer('rating')->nullable()->comment('Rating for the day, can be null if not rated');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('day');
    }
};
