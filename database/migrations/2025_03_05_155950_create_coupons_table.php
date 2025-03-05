<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('discount_amount', 10, 2);
            $table->integer('usage_limit')->nullable();
            $table->date('expiration_date');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('coupons');
    }
};
