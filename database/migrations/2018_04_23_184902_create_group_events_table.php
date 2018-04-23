<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_events', function (Blueprint $table) {
            $table->increments('id');
            $table -> unsignedInteger('group_id');
            $table -> time('start_time');
            $table -> time('end_time');
            $table -> char('type', 1);
            $table -> unsignedTinyInteger('repeat');
            $table -> mediumText('description');
            $table->timestamps();

            // set a foreign key
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
        Schema::table('group_events', function(Blueprint $table) {
            $table -> dropForeign('group_events_group_id_foreign');
        });
        Schema::dropIfExists('group_events');
    }
}
