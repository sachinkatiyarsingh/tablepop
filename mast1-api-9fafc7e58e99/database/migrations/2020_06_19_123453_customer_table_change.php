<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CustomerTableChange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
           // $table->string('socialId')->after('status')->nullable(); 
          //  $table->string('socialType')->after('status')->nullable(); 
            $table->string('invitationId')->after('status')->nullable(); 
            $table->string('invitationCode')->after('status')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function(Blueprint $table) {
            $table->dropColumn(['invitationId','invitationCode']);
        });
    }
}
