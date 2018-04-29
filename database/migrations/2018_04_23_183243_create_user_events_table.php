<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_events', function (Blueprint $table) {
            $table->increments('id');
            $table -> unsignedInteger('user_id');
            $table -> string('title', 35);
            $table -> date('date');
            $table -> time('start_time');
            $table -> time('end_time');
            $table -> char('type', 1); // a - annual; m - monthly; w - weekly; d - daily; s - single
            $table -> unsignedTinyInteger('repeat');
            $table -> mediumText('description');
            $table->timestamps();

            // set a foreign key
            $table -> foreign('user_id') -> references('id') -> on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_events', function(Blueprint $table) {
            $table -> dropForeign('user_events_user_id_foreign');
        });
        Schema::dropIfExists('user_events');
    }
}
