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
     Schema::create('table_stock_reservations', function (Blueprint $table) {

        $table->id();

        $table->foreignId('product_id')
              ->constrained('table_products');

        $table->foreignId('cart_id')
              ->constrained('table_carts');

        $table->integer('quantity');

        $table->enum('status', [
            'active',
            'cancelled',
            'expired',
            'confirmed'
        ]);

        $table->timestamp('expires_at');

        $table->timestamps();

    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_stock_reservations');
    }
};
