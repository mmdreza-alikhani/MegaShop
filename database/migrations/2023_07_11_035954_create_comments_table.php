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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreignId('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->foreignId('article_id')->nullable();
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');

            $table->foreignId('news_id')->nullable();
            $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');

            $table->unsignedInteger('reply_of')->default(0);

            $table->boolean('approved')->default(0);
            $table->text('text');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
