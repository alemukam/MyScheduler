<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table -> increments('id');
            $table -> string('name');
            $table -> string('email');
            $table -> string('title', 32);
            $table -> mediumText('message');
            $table -> char('status', 1); // p - pending; a - approved; n - new; r - rejected
            $table -> char('type', 1); // 0 - submitted form; 1 - group approval
            $table -> mediumText('admin_message') -> nullable(); // in case of 'r'
            $table -> unsignedInteger('group_id') -> nullable(); // only for the type 1
            $table -> timestamps();

            // define foreign keys
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
        Schema::table('admin_notifications', function (Blueprint $table) {
            $table -> dropForeign('admin_notifications_group_id_foreign');
        });

        Schema::dropIfExists('admin_notifications');
    }
}
