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
        Schema::create('whatsapp_widgets', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('whatsapp_number',20);
            $table->string('status_message');
            $table->string('profile_image');
            $table->string('widget_position',20);
            $table->text('welcome_message');
            $table->text('secondary_message');
            $table->string('button1',50);
            $table->string('button2',50);
            $table->string('button3',50);
            $table->integer('user_id');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_widgets');
    }
};
