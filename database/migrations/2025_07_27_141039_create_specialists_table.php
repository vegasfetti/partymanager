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
        Schema::create('specialists', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('video_link')->nullable();
            $table->integer('price');
            $table->enum('price_type', allowed: ['per_hour', 'per_day', 'per_service'])->default('per_service');
            $table->string('phone');
            $table->string('email');
            $table->string('vkontacte')->nullable();
            $table->string('telegram')->nullable();
            $table->string('website')->nullable();
            $table->text(column: 'price_text')->nullable();


            $table->text('skills')->nullable();
            $table->text(column: 'equipment')->nullable();
            $table->text(column: 'languages')->nullable();

            $table->enum('experience', allowed: ['less_than_1', '1_3_years', '3_5_years', 'more_than_5']);
            $table->enum('subject_type', ['individual', 'company'])->default('individual');
            $table->boolean('is_contract')->default(false);

            $table->dateTime('promoted_until')->nullable();

            $table->enum('status', ['on_moderation', 'verify', 'canceled'])->default('on_moderation');
            $table->timestamp('documents_verified_at')->nullable();
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('subcategory_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specialists');
    }
};
