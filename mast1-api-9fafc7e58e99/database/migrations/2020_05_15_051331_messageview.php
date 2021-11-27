<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Messageview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("
        CREATE VIEW recent_message_view AS SELECT G1.* FROM messages AS G1 JOIN (SELECT groupId, max(created_at) as mostrecent FROM messages group by groupId) AS G2 ON G2.groupId = G1.groupId and G2.mostrecent = G1.created_at ORDER BY G1.id DESC");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS recent_message_view');
    }
}
