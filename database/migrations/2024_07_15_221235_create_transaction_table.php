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
        Schema::create('transaction', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->string('transaction_number')->unique();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->bigInteger('total_item')->default(0);
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->decimal('discount_amount', 10, 2)->default(0.00)->nullable();
            $table->decimal('grand_total', 10, 2)->default(0.00);
            $table->decimal('pay_amount', 10, 2)->default(0.00);
            $table->decimal('change_amount', 10, 2)->default(0.00);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('status', ['pending', 'completed', 'return'])->default('pending');
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->timestamp('transaction_date')->nullable();
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();
            
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('customer_id')
                ->references('customer_id')
                ->on('customer')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('discount_id')
                ->references('discount_id')
                ->on('discount')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('payment_method_id')
                ->references('payment_method_id')
                ->on('payment_method')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction');
    }
};
