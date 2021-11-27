<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMilesstoneAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('milestones', function (Blueprint $table) {
            $table->integer('adminCommission')->after('amount')->default(0)->comment('Admin Tax in %'); 
            $table->integer('totalPayment')->after('amount')->default(0); 
            $table->string('invoice')->after('totalPayment')->nullable(); 
            $table->string('invoiceId')->after('totalPayment')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('milestones', function (Blueprint $table) {
            $table->dropColumn(['adminCommission','totalPayment','invoice','invoiceId']);
        });
    }
}
