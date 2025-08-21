<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('smart_orders', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->string('social_network');
            $table->text('current_date');
            $table->text('comment')->nullable();
            $table->enum('status', ['waiting', 'verify', 'canceled'])->default('waiting');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Связующая таблица
        Schema::create('smart_order_specialists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('smart_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('specialist_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['waiting', 'verify', 'canceled'])->default('waiting');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smart_order_specialists');
        Schema::dropIfExists('smart_orders');
    }
};
