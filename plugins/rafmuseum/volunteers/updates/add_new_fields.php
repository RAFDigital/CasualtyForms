<?php namespace RafMuseum\Volunteers\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddNewFields extends Migration
{

    public function up()
    {
        Schema::table('users', function($table)
        {
            $table->integer('forms_transcribed')->default(0);
        });
    }

    public function down()
    {
        Schema::table('users', function($table)
        {
            $table->dropColumn(['forms_transcribed']);
        });
    }

}
