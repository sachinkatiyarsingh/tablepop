<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ShareEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('share_events', function (Blueprint $table) {
           $table->increments('id')->index();
           $table->integer('eventId')->default(0);
           $table->integer('customerId')->default(0);
           $table->string('email');
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
        Schema::dropIfExists('share_events');
    }
}
