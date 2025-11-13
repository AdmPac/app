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
        Schema::table('product_types', function (Blueprint $table) {
            $table->integer('code');
        });
        Schema::table('product_statuses', function (Blueprint $table) {
            $table->integer('code');
        });
        Schema::table('status_orders', function (Blueprint $table) {
            $table->integer('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_types', function (Blueprint $table) {
            $table->dropColumn('code');
        });
        Schema::table('product_statuses', function (Blueprint $table) {
            $table->dropColumn('code');
        });
        Schema::table('status_orders', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
};
