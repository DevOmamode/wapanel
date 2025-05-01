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
        Schema::create('webhook_workflows', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 50)->unique();
            $table->integer('organization_id');
            $table->string('name', 128);
            $table->unsignedBigInteger('template_id');
            $table->foreign('template_id')->references('id')->on('templates');
            $table->boolean('status');
            $table->boolean('verified');
            $table->bigInteger('total_call')->default(0);
            $table->bigInteger('total_error')->default(0);
            $table->bigInteger('total_success')->default(0);
            $table->string('map_name')->nullable();
            $table->longText('workflow_data')->nullable();
            $table->longText('last_data')->nullable();
            $table->longText('last_data_mapped')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_workflows');
    }
};