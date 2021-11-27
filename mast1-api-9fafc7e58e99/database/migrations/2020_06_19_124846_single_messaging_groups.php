<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SingleMessagingGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('single_messaging_groups', function (Blueprint $table) {
           $table->increments('id')->index();
           $table->string('groupId');
           $table->integer('createId')->default(0);
           $table->integer('sameId')->default(0);
           $table->integer('adminId')->default(0);
           $table->integer('type')->default(0)->comment('1=>admin,2=>customer,3=>seller');
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
        Schema::dropIfExists('single_messaging_groups');
    }
}
