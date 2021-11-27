<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Customers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->integer('staffId')->default(0);
            $table->string('name',50);
            $table->string('surname',50);
            $table->string('email');
            $table->string('mobile',15)->nullable();
            $table->string('password');
            $table->string('notification')->nullable();
            $table->integer('country_id');
            $table->integer('state_id');
            $table->string('status')->default(0);
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
        Schema::dropIfExists('customers');
    }
}
