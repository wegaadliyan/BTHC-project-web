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
            // Kolom-kolom yang hilang dari implementasi controller
            if (!Schema::hasColumn('payments', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('payments', 'product_id')) {
                $table->unsignedBigInteger('product_id')->nullable()->after('user_name');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            }
            if (!Schema::hasColumn('payments', 'custom_product_id')) {
                $table->unsignedBigInteger('custom_product_id')->nullable()->after('product_id');
                $table->foreign('custom_product_id')->references('id')->on('custom_products')->onDelete('set null');
            }
            if (!Schema::hasColumn('payments', 'alamat_nama')) {
                $table->string('alamat_nama')->after('status');
            }
            if (!Schema::hasColumn('payments', 'alamat_telp')) {
                $table->string('alamat_telp')->after('alamat_nama');
            }
            if (!Schema::hasColumn('payments', 'alamat_provinsi')) {
                $table->string('alamat_provinsi')->after('alamat_telp');
            }
            if (!Schema::hasColumn('payments', 'alamat_kota')) {
                $table->string('alamat_kota')->after('alamat_provinsi');
            }
            if (!Schema::hasColumn('payments', 'alamat_kecamatan')) {
                $table->string('alamat_kecamatan')->after('alamat_kota');
            }
            if (!Schema::hasColumn('payments', 'alamat_kodepos')) {
                $table->string('alamat_kodepos')->after('alamat_kecamatan');
            }
            if (!Schema::hasColumn('payments', 'alamat_detail')) {
                $table->text('alamat_detail')->after('alamat_kodepos');
            }

            // Hapus kolom yang tidak lagi digunakan oleh controller baru
            if (Schema::hasColumn('payments', 'date')) {
                $table->dropColumn('date');
            }
            if (Schema::hasColumn('payments', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('payments', 'subtotal')) {
                $table->dropColumn('subtotal');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['product_id']);
            $table->dropForeign(['custom_product_id']);

            $table->dropColumn([
                'user_id', 
                'product_id', 
                'custom_product_id',
                'alamat_nama',
                'alamat_telp',
                'alamat_provinsi',
                'alamat_kota',
                'alamat_kecamatan',
                'alamat_kodepos',
                'alamat_detail'
            ]);

            // Tambahkan kembali kolom yang dihapus di 'up'
            $table->date('date')->nullable();
            $table->string('email')->nullable();
            $table->decimal('subtotal', 10, 2)->nullable();
        });
    }
};