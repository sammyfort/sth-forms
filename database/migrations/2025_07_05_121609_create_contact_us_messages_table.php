<?php

use App\Enums\ContactUsMessageStatus;
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
        Schema::create('contact_us_messages', function (Blueprint $table) {
             $table->id();
             $table->uuid();
             $table->string('name');
             $table->string('mobile');
             $table->string('email')->nullable();
             $table->text('message');
             $table->string('status')->default(ContactUsMessageStatus::NEW);
             $table->foreignId('created_by_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_us_messages');
    }
};
