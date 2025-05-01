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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('uuid', 50)->unique()->nullable();
            $table->integer('organization_id');
            $table->string('name')->nullable();
            $table->char('catalog_id')->nullable();
            $table->longText('image')->nullable();
            $table->char('retailer_id')->nullable();
            $table->char('product_id')->nullable();
            $table->text('price')->nullable();
            $table->text('assign_label')->nullable();
            $table->text('currency')->nullable();
            $table->text('availability')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('status')->default(0);
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};