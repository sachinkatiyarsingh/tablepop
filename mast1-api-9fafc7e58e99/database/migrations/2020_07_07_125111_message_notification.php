<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MessageNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_notifications', function (Blueprint $table) {
           $table->id();
           $table->string('groupId');
           $table->integer('userId')->default(0);
           $table->integer('type')->default(0)->comment('1=>admin,2=>customer,3=>seller');;
           $table->text('message')->nullable();
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
        Schema::dropIfExists('message_notifications');
    }
}
