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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id('purchase_id');
            $table->unsignedBigInteger('stock_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->decimal('purchase_price', 10, 2);
            $table->bigInteger('stock_amount');
            $table->string('invoice_number')->unique();
            $table->text('notes')->nullable();
            $table->timestamp('purchase_date');
            $table->boolean('is_return')->default(0);
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();

            $table->foreign('stock_id')
            ->references('stock_id')
            ->on('stock')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('product_id')
            ->references('product_id')
            ->on('product')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('supplier_id')
            ->references('supplier_id')
            ->on('supplier')
            ->onDelete('cascade')
            ->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
