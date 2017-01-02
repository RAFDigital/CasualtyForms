<?php namespace RafMuseum\UserTimelogs\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateRafmuseumUsertimelogsLogs2 extends Migration
{
    public function up()
    {
        Schema::table('rafmuseum_usertimelogs_logs', function($table)
        {
            $table->dateTime('signin_time')->nullable(false)->unsigned(false)->default(null)->change();
            $table->dateTime('signout_time')->nullable()->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('rafmuseum_usertimelogs_logs', function($table)
        {
            $table->time('signin_time')->nullable(false)->unsigned(false)->default(null)->change();
            $table->time('signout_time')->nullable()->unsigned(false)->default(null)->change();
        });
    }
}
