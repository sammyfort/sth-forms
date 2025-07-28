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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->mediumText('short_description');
            $table->longText('description');
            $table->string('website')->nullable();
            $table->string('price');
            $table->boolean('is_negotiable')->default(false);
            $table->string('first_mobile');
            $table->string('second_mobile')->nullable();
            $table->foreignId('region_id')->constrained();
            $table->string('town');

            $table->foreignId('created_by_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
