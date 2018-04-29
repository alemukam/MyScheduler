<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGroupRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_group_relations', function (Blueprint $table) {
            $table -> increments('id');
            $table -> unsignedInteger('user_id');
            $table -> unsignedInteger('group_id');
            $table -> char('status', 1); // a - approved, p - pending, b - blocked
            $table->timestamps();

            // define foreign keys
            $table -> foreign('user_id') -> references('id') -> on('users');
            $table -> foreign('group_id') -> references('id') -> on('groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_group_relations', function (Blueprint $table) {
            $table -> dropForeign('user_group_relations_user_id_foreign');
            $table -> dropForeign('user_group_relations_group_id_foreign');
        });
        Schema::dropIfExists('user_group_relations');
    }
}
