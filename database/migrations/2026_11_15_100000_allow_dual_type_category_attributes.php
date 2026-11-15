<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * یک ویژگی می‌تواند هم‌زمان «ویژگی تنوع» و «ویژگی فیلتر» یک دسته باشد
 * (مثلاً رنگ هم تنوع محصول را می‌سازد و هم در فیلترها نمایش داده می‌شود).
 * کلید اصلی قبلی (attribute_id, category_id) این حالت را غیرممکن می‌کرد
 * و ثبت دسته با خطای Duplicate entry شکست می‌خورد.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('category_attributes', function (Blueprint $table) {
            // ایندکس مستقل تا حذف کلید اصلی، کلید خارجی attribute_id را از کار نیندازد.
            $table->index('attribute_id', 'category_attributes_attribute_id_index');
        });

        Schema::table('category_attributes', function (Blueprint $table) {
            $table->dropPrimary(['attribute_id', 'category_id']);
        });

        Schema::table('category_attributes', function (Blueprint $table) {
            $table->unique(
                ['category_id', 'attribute_id', 'type'],
                'category_attributes_category_attribute_type_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('category_attributes', function (Blueprint $table) {
            $table->dropUnique('category_attributes_category_attribute_type_unique');
        });

        Schema::table('category_attributes', function (Blueprint $table) {
            $table->primary(['attribute_id', 'category_id']);
        });

        Schema::table('category_attributes', function (Blueprint $table) {
            $table->dropIndex('category_attributes_attribute_id_index');
        });
    }
};
