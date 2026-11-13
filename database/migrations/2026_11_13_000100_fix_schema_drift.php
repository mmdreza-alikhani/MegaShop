<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * رفع فاصله بین مدل/فرم‌ها و اسکیمای دیتابیس (schema drift).
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // قبل از دریافت authority از درگاه، تراکنش با توکن موقت ساخته می‌شود.
            $table->string('token')->nullable()->change();
            // زرین‌پال شماره کارت پرداخت‌کننده را برمی‌گرداند.
            $table->string('card_pan')->nullable()->after('ref_id');
        });

        Schema::table('products', function (Blueprint $table) {
            // در فرم ایجاد/ویرایش محصول و در Requestها استفاده می‌شود.
            $table->unsignedInteger('delivery_amount_per_product')->default(0)->after('delivery_amount');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('card_pan');
            $table->string('token')->nullable(false)->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('delivery_amount_per_product');
        });
    }
};
