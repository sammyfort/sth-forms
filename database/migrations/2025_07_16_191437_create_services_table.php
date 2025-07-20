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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->uuid();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->longText('description');
            $table->string('first_mobile');
            $table->string('business_name')->nullable();
            $table->string('second_mobile')->nullable();
            $table->string('email')->nullable();
            $table->tinyText('address')->nullable();
            $table->foreignId('region_id')->constrained();
            $table->string('town');
            $table->string('gps')->nullable();

           // $table->decimal('charge_amount')->nullable();
           // $table->string('estimated_days')->nullable();

            $table->foreignId('created_by_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
