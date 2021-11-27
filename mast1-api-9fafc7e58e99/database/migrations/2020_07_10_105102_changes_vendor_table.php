<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangesVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
         //   $table->dropColumn(['experiencePlanning','otherExperiencePlanning']);
            $table->string('servicesCategory')->after('experiencePlanning')->nullable($value = true);
            $table->string('serviceSubCategory')->after('servicesCategory')->nullable($value = true);
            $table->string('otherServices')->after('serviceSubCategory')->nullable($value = true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn(['servicesCategory','serviceSubCategory','otherServices']);
        });
    }
}
