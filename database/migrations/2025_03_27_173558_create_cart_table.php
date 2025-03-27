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
        Schema::create('cart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->unsignedBigInteger('collection_id')->nullable()->default(0);
            $table->integer('quantity');
            $table->decimal('amount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->timestamps();

            // Foreign key constraint (optional)
            $table->foreign('collection_id')->references('id')->on('product_collections')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};
