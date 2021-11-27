<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldVendor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            
            $table->id();
            $table->integer('sellerId')->default(0);
            $table->text('resume');
            $table->text('contract');
            $table->text('coverLetter');
            $table->string('resumeFile');
            $table->string('coverLetterFile');    
            $table->text('portfolio');
            $table->string('hearTablepop');
            $table->string('referredBy');
            $table->string('experience');
            $table->string('dedicateTablePopClient');
            $table->string('willingWork');
            $table->string('software');
            $table->string('otherSoftware');
            $table->string('employmentSituation');
            $table->string('workingindustry');
            $table->string('degrees');
            $table->string('otherDegrees');
            $table->string('experiencePlanning');
            $table->string('otherExperiencePlanning');
            $table->string('pricingModel');
            $table->string('plannedSimultaneously');
            $table->string('teamOfVendors');
            $table->string('creditToExecute');
            $table->string('eventClient');
            $table->string('interestedTablepop');
            $table->string('references');
            $table->string('personalityPlanners');
            $table->string('planEventsCompany');
            $table->string('eventSoftware');
            $table->string('promotionSocialMedia');
            $table->string('eventSuccessful');
            $table->string('gamePlan');
            $table->string('successfulPlanningExperience');
            $table->string('experienceNegotiating');
            $table->string('stressPlanning');
            $table->string('kickoffMeeting');
            $table->string('plannedMoreOneEvent');
            $table->string('prioritizeDeadlines');
            $table->string('difficultClient');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendors');
    }
}
