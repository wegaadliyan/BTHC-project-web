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
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'order_id')) {
                $table->string('order_id')->after('id')->index();
            }
            if (!Schema::hasColumn('payments', 'shipping_cost')) {
                $table->decimal('shipping_cost', 10, 2)->after('price')->default(0);
            }
            if (!Schema::hasColumn('payments', 'total_price')) {
                $table->decimal('total_price', 10, 2)->after('shipping_cost')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['order_id', 'shipping_cost', 'total_price']);
        });
    }
};