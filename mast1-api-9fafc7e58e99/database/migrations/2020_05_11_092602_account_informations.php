<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AccountInformations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_informations', function (Blueprint $table) {
            $table->id();
            $table->integer('sellerId')->default(0);
            $table->string('bankName')->nullable($value = true);
            $table->string('sortCode')->nullable($value = true);
            $table->text('accountNo')->nullable($value = true);
            $table->text('addressOne')->nullable($value = true);
            $table->text('addressTwo')->nullable($value = true);
            $table->text('addressThree')->nullable($value = true);
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
        Schema::dropIfExists('account_informations');
    }
}
