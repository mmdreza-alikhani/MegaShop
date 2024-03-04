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
            $table->id();
            $table->string('name');

            $table->foreignId('brand_id');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');

            $table->foreignId('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->foreignId('platform_id')->default(0);
            $table->foreign('platform_id')->references('id')->on('platforms')->onDelete('cascade');

            $table->string('slug')->unique();
            $table->string('primary_image');
            $table->text('description');
            $table->integer('status')->default(1);
            $table->boolean('is_active')->default(1);
            $table->unsignedInteger('delivery_amount')->default(0);
            $table->unsignedInteger('delivery_amount_per_product')->nullable();

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
