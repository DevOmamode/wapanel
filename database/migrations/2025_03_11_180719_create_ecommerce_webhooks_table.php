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
        Schema::create('ecommerce_webhooks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('uuid', 50)->unique()->nullable();
            $table->char('catalog_id')->nullable();
            $table->integer('organization_id')->nullable();
            $table->text('webhook_url')->nullable();
            $table->string('webhook_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecommerce_webhooks');
    }
};
