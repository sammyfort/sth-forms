<?php

use App\Enums\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('signboard_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('signboard_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained('signboard_subscription_plans')->nullOnDelete();
            $table->float('amount', 2)->nullable();
            $table->string('payment_reference');
            $table->string('payment_status')->default(PaymentStatus::PENDING);
            $table->string('payment_channel')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->string('receipt_number')->nullable();
            $table->foreignId('created_by_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
