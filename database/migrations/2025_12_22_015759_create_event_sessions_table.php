<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('event_sessions', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->dateTime('start_time');
        $table->dateTime('end_time');
        $table->string('room');
        $table->foreignId('event_id')->constrained()->onDelete('cascade');
        $table->foreignId('chair_id')->nullable()->constrained('users');
        $table->timestamps();
    });
}
};
