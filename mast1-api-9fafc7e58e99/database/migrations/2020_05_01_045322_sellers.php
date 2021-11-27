<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Sellers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->integer('staffId')->default(0);
            $table->string('userName');
            $table->string('firstName',100);
            $table->string('lastName',100);
            $table->string('email');
            $table->string('mobileNo')->nullable();
            $table->string('password');
            $table->string('location');
            $table->string('latitude');
            $table->string('longitude');
            $table->text('profileImage')->nullable();
            $table->integer('type')->default(0)->comment('1=>vendor,2=>planner');
            $table->integer('status')->default(0);
           
           
           
            $table->rememberToken();
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
        Schema::dropIfExists('sellers');
    }
}
