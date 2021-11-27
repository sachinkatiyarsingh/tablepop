<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Moodboards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moodboards', function (Blueprint $table) {
            $table->id();      
            $table->integer('sellerId')->default(0); 
            $table->integer('eventId')->default(0); 
            $table->string('name');  
            $table->string('description')->nullable(); ;  
            $table->string('previewImage');  
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
        Schema::dropIfExists('moodboards');
    }
}
