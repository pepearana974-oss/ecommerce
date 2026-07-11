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
        Schema::create('table_orders', function (Blueprint $table) {

        $table->id();

        $table->foreignId('user_id')
              ->constrained('users');

        $table->string('folio');

        $table->enum('status', [
            'pending',
            'completed',
            'cancelled'
        ]);

        $table->decimal('subtotal', 8, 2);
        $table->decimal('total', 8, 2);

        $table->timestamps();

        $table->string('stripe_session_id')->nullable();
        $table->string('stripe_payment_intent')->nullable();

    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_orders');
    }
};
