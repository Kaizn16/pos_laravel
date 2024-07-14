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
        Schema::create('product', function (Blueprint $table) {
            $table->id('product_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('product_code')->nullable();
            $table->string('product_name');
            $table->decimal('purchase_price', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->unsignedBigInteger('uom_id')->nullable(); // unit of measure per selling
            $table->string('product_image', 255)->nullable();
            $table->boolean('status')->default(0);
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();
            $table->softDeletes();
        
            $table->foreign('category_id')
                ->references('category_id')
                ->on('category')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('uom_id')
                ->references('uom_id')
                ->on('uom')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
