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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade'); 
            $table->integer('scientific_quality')->nullable(); // 1-5
            $table->integer('relevance')->nullable(); // 1-5
            $table->integer('originality')->nullable(); // 1-5
            $table->text('comments')->nullable();
            $table->enum('recommendation', ['accept', 'reject', 'revise'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
