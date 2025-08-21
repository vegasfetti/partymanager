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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('link')->nullable();
            $table->string('image')->default('banner_upload/no-photo.png');
            $table->boolean('status')->default(true);
            $table->boolean('is_promo')->default(false);
            $table->enum('type', ['main', 'specialists'])->default('main');
            $table->foreignId('city_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
