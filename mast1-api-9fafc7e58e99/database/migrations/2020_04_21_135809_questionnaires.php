<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Questionnaires extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->id();
            $table->integer('customerId');
            $table->string('levelOfService');
            $table->string('levelOfServicePlanningType');
            $table->string('partyPlanner');
            $table->string('budgetRangeStart');
            $table->string('budgetRangeEnd');
            $table->string('helpedBudget');
            $table->string('helpedBudgetOther');
            $table->string('premiumEvent');
            $table->string('confirmationPartyPlanner');
            $table->string('name');
            $table->string('email');
            $table->string('mobile');
            $table->string('eventName');
            $table->string('typeEvent');
            $table->string('eventPlanning');
            $table->string('guestExpectStart');
            $table->string('guestExpectEnd');
            $table->string('farEvent');
            $table->string('farEventDate');
            $table->string('partyPlaningService');
            $table->string('vennu');
            $table->string('themeEvent');
            $table->string('themeEventOther');
            $table->string('addPhotos');
            $table->string('weddindIdeas');
            $table->string('anytningPartyPlanner');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
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
        Schema::dropIfExists('questionnaires');
    }
}
