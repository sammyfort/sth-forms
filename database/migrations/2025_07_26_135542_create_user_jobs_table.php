<?php

use App\Enums\JobStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_jobs', function (Blueprint $table) {
            $table->id();
            $table->uuid();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('company_name');
            $table->string('title');
            $table->string('slug')->unique();
            $table->tinyText('summary');
            $table->longText('description');
            $table->string('job_type'); // "Full-time", "Part-time", "Contract", "Internship"
            $table->string('work_mode'); // onsite, remote, hybrid
            $table->foreignId('region_id')->nullable();
            $table->string('town')->nullable();
            $table->string('salary')->nullable();
            $table->mediumText('how_to_apply')->nullable();
            $table->string('application_link')->nullable();
            $table->timestamp('deadline');
            $table->string('status')->default(JobStatus::ACTIVE);

            $table->foreignId('created_by_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
