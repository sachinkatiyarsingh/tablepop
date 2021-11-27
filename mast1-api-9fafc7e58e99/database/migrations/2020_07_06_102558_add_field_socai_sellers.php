<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldSocaiSellers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->text('facebook')->nullable()->after('free');
            $table->text('twitter')->nullable()->after('facebook');
            $table->text('pinterest')->nullable()->after('twitter');
            $table->text('website')->nullable()->after('pinterest');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sellers', function(Blueprint $table) {
            $table->dropColumn(['facebook','twitter','pinterest','website']);
        });
    }
}
