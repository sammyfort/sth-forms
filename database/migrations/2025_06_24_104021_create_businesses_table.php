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
        Schema::create('businesses', function (Blueprint $table) {
             $table->id();
             $table->uuid();

             $table->string('name');
             $table->string('email')->unique();
             $table->string('description');
             $table->string('mobile')->unique();
             $table->string('facebook')->nullable();
             $table->string('x')->nullable();
             $table->string('linkedin')->nullable();
             $table->string('instagram')->nullable();

             $table->foreignId('created_by_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
