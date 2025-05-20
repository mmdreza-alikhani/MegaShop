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
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('address_id')->constrained('user_addresses')->cascadeOnDelete();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->integer('status');
            $table->unsignedInteger('total_amount');
            $table->unsignedInteger('delivery_amount');
            $table->unsignedInteger('coupon_amount');
            $table->unsignedInteger('paying_amount');
            $table->enum('payment_type', ['pos', 'cash', 'shabaNumber', 'cardToCard', 'online']);
            $table->integer('payment_status');
            $table->text('description')->nullable();
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
