<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangesEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->dropColumn(['partyPlanner','budgetRangeStart','budgetRangeEnd','helpedBudget','helpedBudgetOther','addPhotos','partyPlaningService']);
            $table->string('partyPlaningServiceCatgeory')->after('farEvent')->nullable($value = true);
            $table->string('partyPlaningServiceSubCatgeory')->after('partyPlaningServiceCatgeory')->nullable($value = true);
            $table->string('vennuValue')->after('vennu')->nullable($value = true);
            $table->string('hearAbout')->after('vennuValue')->nullable($value = true);
            $table->string('hearAboutOther')->after('hearAbout')->nullable($value = true);
            $table->string('eventPlanningOther')->after('eventPlanning')->nullable($value = true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->dropColumn(['eventPlanningOther','partyPlaningServiceCatgeory','partyPlaningServiceSubCatgeory','vennuValue','hearAbout','hearAboutOther']);
        });
    }
    
}
