<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CustomerAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_address', function (Blueprint $table) {
           $table->increments('id')->index();
           $table->integer('customerId')->default(0);
           $table->integer('country')->default(0);
           $table->string('phoneNumber');
           $table->text('street');
           $table->integer('status')->default(0)->comment('0=>address,1=>deletedAddress');
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
        Schema::dropIfExists('customer_address');
    }
}
