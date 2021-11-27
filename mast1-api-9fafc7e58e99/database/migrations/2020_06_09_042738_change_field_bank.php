<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldBank extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_informations', function (Blueprint $table) {
            $table->dropColumn('sortCode');
            $table->dropColumn('addressOne');
            $table->dropColumn('addressTwo');
            $table->dropColumn('addressThree');
            $table->string('routingNumber')->after('accountNo')->nullable();          
            $table->string('accountHolderName')->after('routingNumber')->nullable();    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_informations', function (Blueprint $table) {
            $table->dropColumn([]);
        });
    }
}
