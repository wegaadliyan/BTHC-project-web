<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('custom_products', function (Blueprint $table) {
            // Drop old column if exists
            if (Schema::hasColumn('custom_products', 'charm')) {
                $table->dropColumn('charm');
            }
            if (Schema::hasColumn('custom_products', 'charm_images')) {
                $table->dropColumn('charm_images');
            }
        });

        Schema::table('custom_products', function (Blueprint $table) {
            // Add new charm columns with images
            $table->string('charm_1')->nullable()->after('size');
            $table->string('charm_1_image')->nullable()->after('charm_1');
            
            $table->string('charm_2')->nullable()->after('charm_1_image');
            $table->string('charm_2_image')->nullable()->after('charm_2');
            
            $table->string('charm_3')->nullable()->after('charm_2_image');
            $table->string('charm_3_image')->nullable()->after('charm_3');
        });
    }

    public function down(): void
    {
        Schema::table('custom_products', function (Blueprint $table) {
            $table->dropColumn([
                'charm_1', 'charm_1_image',
                'charm_2', 'charm_2_image',
                'charm_3', 'charm_3_image'
            ]);
        });
    }
};
