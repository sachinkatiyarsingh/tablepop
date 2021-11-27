<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->integer('fromId')->after('id')->default(0);
            $table->integer('toId')->after('fromId')->default(0);
            $table->string('sendType')->after('readStatus')->nullable($value = true);
        });
    }

   
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['fromId','toId','sendType']);
        });
    }
}
