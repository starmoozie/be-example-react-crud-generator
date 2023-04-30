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
        Schema::create('sales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('customer_id')->nullable()->index();
            $table->date('date')->nullable();
            $table->string('receipt_number')->nullable();
            $table->string('checkout_amount')->nullable();
            $table->json('items')->nullable()->comment('product name', 'sell_price', 'buy_price');
            $table->uuid('payment_method_id')->nullable()->index();
            $table->string('pay_amount')->nullable();
            $table->string('refund_amount')->nullable();
            $table->string('brutto')->nullable();
            $table->string('netto')->nullable();
            $table->string('refund')->nullable();
            $table->string('unpaid')->nullable();
            $table->boolean('refund_payabled')->default(false);
            $table->timestamps();

            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
