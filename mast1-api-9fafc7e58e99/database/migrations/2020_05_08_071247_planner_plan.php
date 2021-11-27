<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PlannerPlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plannerplans', function (Blueprint $table) {
            $table->id();
            $table->integer('sellerId')->default(0);
            $table->string('title');
            $table->text('description');
            $table->integer('isCustom')->default(0)->nullable();
            $table->float('regularPrice')->default(0)->nullable();
            $table->float('salePrice')->default(0.00)->nullable();
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
        Schema::dropIfExists('plannerPlans');
    }
}
