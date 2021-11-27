<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CustomeSelectVendor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_vendors', function (Blueprint $table) {
            $table->id();      
            $table->integer('customerId')->default(0); 
            $table->integer('eventId')->default(0);    
            $table->integer('sellerId')->default(0);    
            $table->integer('status')->default(0);    
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
        Schema::dropIfExists('customer_vendors');
    }
}
