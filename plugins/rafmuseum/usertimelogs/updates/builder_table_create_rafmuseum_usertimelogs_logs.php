<?php namespace RafMuseum\UserTimelogs\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateRafmuseumUsertimelogsLogs extends Migration
{
    public function up()
    {
        Schema::create('rafmuseum_usertimelogs_logs', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->string('session_id');
            $table->timestamp('signin_time')->useCurrent();
            $table->timestamp('signout_time')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rafmuseum_usertimelogs_logs');
    }
}
