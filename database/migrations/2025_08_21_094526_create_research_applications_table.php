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
        Schema::create('research_applications', function (Blueprint $table) {
             $table->id();
             $table->uuid();
             // voucher
            $table->foreignId('category_id')->constrained();
            $table->string('voucher_code');
            // principal_investigator
            $table->string('title');
            $table->string('surname');
            $table->string('other_name');
            $table->string('email');
            $table->string('telephone');
            $table->text('postal_address');
            $table->string('institution_investigator');
            $table->string('department_unit');
            $table->string('directorate');

            // study_coordinator
            $table->string('study_coordinator_name');
            $table->string('coordinator_email');
            $table->text('local_collaborator_name');
            $table->text('local_collaborator_address');
            $table->json('staff_categories');

            // research_work
            //$table->string('research_category');
            $table->string('observation_study');
            $table->string('interventional_study');
            $table->string('study_location_level');
            $table->json('documents');

            // research_details
            $table->string('study_location_in_sth');
            $table->text('study_title');
            $table->json('study_design');
            $table->text('study_objectives');

            // inclusion_and_exclusion_criteria
            $table->longText('inclusion_criteria');
            $table->longText('exclusion_criteria');
            $table->unsignedInteger('sample_size');

            // study_participation_duration
            $table->date('proposed_start_date');
            $table->date('proposed_end_date');
            $table->string('proposed_study_duration');
            $table->date('recruitment_start_date');
            $table->date('recruitment_end_date');
            $table->string('source_of_fund');
            $table->string('research_fund_base');
            $table->string('research_total_budget');
            $table->string('study_support_from_sth');
            $table->text('equipments_needed');
            $table->text('physical_financial_support');

            // communication_publication
            $table->enum('organize_forum', ['yes', 'no']);
            $table->enum('post_on_sth_website', ['yes', 'no']);
            $table->boolean('agreement');

             $table->foreignId('created_by_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_applications');
    }
};
