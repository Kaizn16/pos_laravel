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
        Schema::create('transaction_detail', function (Blueprint $table) {
            $table->id('transcation_detail_id');
            $table->unsignedBigInteger('transcation_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('product_price', 10, 2);
            $table->bigInteger('quantity');
            $table->decimal('total', 10, 2);
            $table->boolean('is_refunded')->default(0);
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();

            $table->foreign('transcation_id')
            ->references('transaction_id')
            ->on('transaction')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('product_id')
            ->references('product_id')
            ->on('product')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_detail');
    }
};
