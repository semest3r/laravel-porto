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
        Schema::create('product_img', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('filename')->unique();
            $table->string('path')->unique();
            $table->enum('type_file', ['png', 'jpeg', 'jpg', 'svg']);
            $table->foreignId('product_id')->references('id')->on('products');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_img');
    }
};
