<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SellerFieldAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sellers', function (Blueprint $table) {
          //  $table->string('mobileNo')->after('email')->nullable($value = true);
            $table->string('dob')->after('mobileNo')->nullable($value = true);
            $table->string('gender')->after('mobileNo')->nullable($value = true);
            $table->integer('countryId')->after('mobileNo')->nullable($value = true);
            $table->text('addressTwo')->after('location')->nullable($value = true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->dropColumn(['dob','gender','countryId','addressTwo']);
        });
    }
}
