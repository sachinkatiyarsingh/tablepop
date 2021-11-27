<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldmoodboardImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('moodboard_images', function (Blueprint $table) {      
            $table->integer('customerId')->after('id')->default(0);   
            $table->integer('status')->after('image')->comment('0=>i=unselect,1=>select')->default(0);                                                
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('moodboard_images', function (Blueprint $table) {
            $table->dropColumn(['status','customerId']);
        });
    }
}
