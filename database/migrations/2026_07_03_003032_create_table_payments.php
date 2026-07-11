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
        Schema::create('table_payments', function (Blueprint $table) {

        $table->id();

        $table->foreignId('order_id')
              ->constrained('table_orders');

        $table->string('provider');
        $table->string('reference');
        $table->string('status');
        $table->decimal('amount', 8, 2);
        $table->longText('payload');

        $table->timestamps();

    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_payments');
    }
};
