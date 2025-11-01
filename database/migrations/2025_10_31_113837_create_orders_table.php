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
            $table->string('value');
        });
        
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('value');
        });
        
        Schema::create('status_orders', function (Blueprint $table) {
            $table->id();
            $table->string('value');
        });
        
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('address_id')->constrained('phones');
            $table->foreignId('phone_id')->constrained('addresses');
            $table->foreignId('status_id')->constrained('status_orders');
            $table->float('total_cost');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('phones');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('status_orders');
    }
};
