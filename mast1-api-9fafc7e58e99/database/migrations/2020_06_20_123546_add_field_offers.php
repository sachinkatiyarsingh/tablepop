<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->integer('type')->comment('0=>planOffer,1=>custom,2=>product custom offer')->change();
            $table->string('cartId')->nullable()->after('type');
            $table->integer('vendorId')->default(0)->after('sellerId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers', function(Blueprint $table) {
            $table->dropColumn(['cartId','vendorId']);
        });
    }
}
