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
        Schema::create('vouchers', function (Blueprint $table) {
             $table->id();
             $table->uuid();

             $table->foreignId('category_id')->constrained();
             $table->string('code')->unique();

             $table->decimal('amount_paid');
             $table->string('payment_channel');
             $table->string('payment_status');
            $table->string('payment_reference');

             $table->string('full_name')->nullable();
             $table->string('email')->nullable();
             $table->string('phone')->nullable();


             $table->boolean('is_used')->default(false);
             $table->timestamp('used_at');
             $table->foreignId('created_by_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
