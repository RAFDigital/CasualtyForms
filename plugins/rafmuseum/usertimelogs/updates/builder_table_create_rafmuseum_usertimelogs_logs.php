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
            $table->time('signin_time');
            $table->time('signout_time');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('rafmuseum_usertimelogs_logs');
    }
}
