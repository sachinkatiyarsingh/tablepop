<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StripeAccountAddField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stripe_accounts', function (Blueprint $table) {   
            $table->string('company')->after('json')->nullable();          
            $table->string('businessType')->after('json')->nullable();          
            $table->string('ssn')->after('businessType')->nullable();          
            $table->string('industry')->after('ssn')->nullable();          
            $table->string('businessWebsite')->after('industry')->nullable();          
            $table->string('ein')->after('businessWebsite')->nullable();          
            $table->string('jobTitle')->after('businessWebsite')->nullable();          
            $table->string('mcc')->after('businessWebsite')->nullable();          
            $table->string('tax_id')->after('businessWebsite')->nullable();          
            $table->string('status')->after('ein')->nullable();                                                
            $table->string('photoIdFront')->after('ein')->nullable();                                                
            $table->string('photoIdBack')->after('ein')->nullable();                                                
            $table->integer('personId')->after('accountId')->default(0);                                             
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stripe_accounts', function (Blueprint $table) {
            $table->dropColumn(['personId','ssn','status','company','businessType','industry','businessWebsite','ein','jobTitle','mcc','tax_id','status','photoIdFront','photoIdBack']);
        });
    }
}
