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
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_id')->nullable();
            $table->foreign('chat_id')->references('id')->on('chats')->nullOnDelete();
            $table->unsignedBigInteger('webhook_id')->nullable();
            $table->foreign('webhook_id')->references('id')->on('webhook_workflows')->cascadeOnDelete();
            $table->text('phone_number')->nullable();
            $table->text('metadata')->nullable();
            $table->text('template')->nullable();
            $table->text('response')->nullable();
            $table->text('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_logs');
    }
};
