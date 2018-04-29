<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table -> increments('id');
            $table -> string('name', 32);
            $table -> mediumText('description');
            $table -> unsignedInteger('moderator_id');
            $table -> char('status', 1);
            $table -> string('img', 32);
            $table -> timestamps();

            // create a foreign key
            $table -> foreign('moderator_id') -> references('id') -> on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 1. drop the foreign key
        Schema::table('groups', function(Blueprint $table) {
            $table -> dropForeign('groups_moderator_id_foreign');
        });
        // 2. drop the table
        Schema::dropIfExists('groups');
    }
}
