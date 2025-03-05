<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->decimal('discount_percentage', 5, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('offers');
    }
};
