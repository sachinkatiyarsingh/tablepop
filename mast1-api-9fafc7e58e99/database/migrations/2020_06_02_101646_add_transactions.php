<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->integer('offerId')->after('planId')->default(0);              
            $table->integer('productId')->after('offerId')->default(0);                
            $table->integer('quantity')->after('productId')->default(0);   
            $table->string('stripeTransactionId')->after('transactionId')->nullable();   
            $table->text('json')->after('status')->nullable();   
            $table->float('vat')->after('amount')->default(0);   
            $table->float('totalAmount')->after('vat')->default(0);   
                   
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['offerId','productId','quantity','stripeTransactionId','json','vat','totalAmount']);
        });
    }
}
