<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.to
     */
    public function up(): void
    {
        Schema::create('signboards', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('business_id')->constrained();
            $table->string('region_id');
            $table->string('slug')->unique();
            $table->string('town');
            $table->string('street')->nullable();
            $table->string('landmark');
            $table->string('blk_number')->nullable();
            $table->string('gps');
            $table->foreignId('created_by_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signboards');
    }
};
