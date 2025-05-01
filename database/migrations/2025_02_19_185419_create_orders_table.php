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
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('uuid', 50)->unique()->nullable();
            $table->char('catalog_id')->nullable();
            $table->integer('organization_id')->nullable();
            $table->char('chat_id')->nullable();
            $table->json('product_items')->nullable();
            $table->char('buyer')->nullable();
            $table->char('phone_number')->nullable();
            $table->json('shipping_address')->nullable();
            $table->string('status', 128)->nullable();
            $table->timestamp('ordered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
