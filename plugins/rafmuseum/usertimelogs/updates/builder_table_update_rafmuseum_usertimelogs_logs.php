<?php namespace RafMuseum\UserTimelogs\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateRafmuseumUsertimelogsLogs extends Migration
{
    public function up()
    {
        Schema::table('rafmuseum_usertimelogs_logs', function($table)
        {
            $table->time('signout_time')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('rafmuseum_usertimelogs_logs', function($table)
        {
            $table->time('signout_time')->nullable(false)->change();
        });
    }
}
