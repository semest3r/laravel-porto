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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('name_role');
            $table->string('code_role', 4);
            $table->timestamps();
        });
        Schema::create('user_role', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('role_id')->references('id')->on('roles');
            $table->timestamps();
        });
        Schema::create('group_categories', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('name_group_category')->unique();
            $table->string('code_group_category')->unique();
            $table->timestamps();
        });
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('name_category')->unique();
            $table->string('code_category')->unique();
            $table->foreignId('group_category_id')->references('id')->on('group_categories');
            $table->timestamps();
        });
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('name_product')->unique();
            $table->string('code_product', 6)->unique();
            $table->foreignId('created_by')->references('id')->on('users');
            $table->foreignId('category_id')->references('id')->on('categories');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('product_img', function(Blueprint $table){
            $table->id();
            $table->uuid();
            $table->foreignId('product_id')->references('id')->on('products');
            $table->string('filename')->unique();
            $table->string('path')->unique();
            $table->enum('file_type', ['jpeg', 'jpg', 'png', 'svg']);
            $table->timestamps();
        });
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('email')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        Schema::create('log_blast_email', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('email')->unique();
            $table->enum('status',['success','failure']);
            $table->timestamps();
        });
        Schema::create('auditrails', function (Blueprint $table) {
            $table->id();
            $table->string('name_user');
            $table->string('user_id');
            //$table->foreignId('user_id')->references('id')->on('users');
            //$table->foreignId('role_id')->references('id')->on('roles');
            $table->text('activity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditrails');
        Schema::dropIfExists('log_blast_email');
        Schema::dropIfExists('subscribers');
        Schema::dropIfExists('product_img');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('group_categories');
        Schema::dropIfExists('user_role');
        Schema::dropIfExists('roles');
    }
};
