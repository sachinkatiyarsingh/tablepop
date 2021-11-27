<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GroupMessaging extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_groups', function (Blueprint $table) {
            $table->id();                                  
            $table->integer('questionnaireId')->default(0);
            $table->integer('customerId')->default(0);     
            $table->integer('sellerId')->default(0);       
            $table->integer('adminId')->default(0);  
            $table->integer('type')->default(0)->comment('0=>group,1=>single,2=>sellerGroup');
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
        Schema::dropIfExists('message_groups');
    }
}
