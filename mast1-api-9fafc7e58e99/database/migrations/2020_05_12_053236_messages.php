<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Messages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->integer('sentby')->default(0);
            $table->integer('sentto')->default(0);
            $table->integer('groupId')->default(0);
            $table->text('message');
            $table->text('msgFile')->nullable();
            $table->string('date');
            $table->integer('sendType')->default(0)->comment('1=>admin,2=>customer,3=>seller');
          //  $table->integer('type')->default(0)->comment('1=>admin,2=>customer,3=>seller');
            $table->integer('readStatus')->default(0);
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
        Schema::dropIfExists('messages');
    }
}
