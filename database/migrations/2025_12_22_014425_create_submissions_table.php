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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('abstract');
            $table->string('keywords');
            $table->enum('type', ['oral', 'poster', 'workshop']);
            $table->string('file_path')->nullable(); 
            $table->enum('status', ['pending', 'under_review', 'accepted', 'rejected', 'revise'])->default('pending');
            $table->foreignId('event_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};