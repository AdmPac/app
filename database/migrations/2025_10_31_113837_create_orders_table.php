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
        Schema::create('phones', function (Blueprint $table) {
            $table->id();
            $table->string('value')->default('');
        });
        
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('value')->default('');
        });
        
        Schema::create('status_orders', function (Blueprint $table) {
            $table->id();
            $table->string('value');
        });
        
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('address_id')->nullable()->constrained('addresses')->default(null)->onDelete('set null');
            $table->foreignId('phone_id')->nullable()->constrained('phones')->default(null)->onDelete('set null');
            $table->foreignId('status_id')->constrained('status_orders');
            $table->float('total_cost')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('orders');
        Schema::dropIfExists('phones');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('status_orders');
    }
};
