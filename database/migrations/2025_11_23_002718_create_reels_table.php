<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
    Schema::create('reels', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('reels_url');
    $table->uuid('user_id');
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    $table->string('foundbyme_id')->nullable();
    $table->text('ai_description')->nullable();
    $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
    $table->timestamp('analized_at')->nullable();
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('reels');
    }
};
