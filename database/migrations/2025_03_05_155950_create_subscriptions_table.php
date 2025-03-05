<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('product_id');
            $table->string('duration'); // e.g., 'monthly', 'weekly'
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('subscriptions');
    }
};
