<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Eventplanners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventplanners', function (Blueprint $table) {
            $table->id();
            $table->integer('questionnaireId')->default(0);
            $table->integer('plannerId')->default(0);
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
        Schema::dropIfExists('eventplanners');
    }
}
