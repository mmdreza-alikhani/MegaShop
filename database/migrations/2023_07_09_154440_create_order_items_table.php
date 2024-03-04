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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->foreignId('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->foreignId('product_variation_id');
            $table->foreign('product_variation_id')->references('id')->on('product_variations')->onDelete('cascade');

            $table->unsignedInteger('price');
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('subtotal');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
