<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ایندکس‌های لازم برای کوئری‌های پرتکرار فروشگاه و پنل.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index(['is_active', 'created_at'], 'products_active_created_index');
            $table->index(['category_id', 'is_active'], 'products_category_active_index');
            $table->index('brand_id');
        });

        Schema::table('product_variations', function (Blueprint $table) {
            $table->index(['product_id', 'price'], 'variations_product_price_index');
            $table->index('quantity');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->index(['is_active', 'type', 'created_at'], 'posts_active_type_index');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->index(['commentable_type', 'commentable_id'], 'comments_commentable_index');
            $table->index(['is_active', 'status'], 'comments_visibility_index');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'orders_user_created_index');
            $table->index('status');
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->index(['is_active', 'type'], 'banners_active_type_index');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_active_created_index');
            $table->dropIndex('products_category_active_index');
            $table->dropIndex(['brand_id']);
        });

        Schema::table('product_variations', function (Blueprint $table) {
            $table->dropIndex('variations_product_price_index');
            $table->dropIndex(['quantity']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('posts_active_type_index');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex('comments_commentable_index');
            $table->dropIndex('comments_visibility_index');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_user_created_index');
            $table->dropIndex(['status']);
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->dropIndex('banners_active_type_index');
        });
    }
};
