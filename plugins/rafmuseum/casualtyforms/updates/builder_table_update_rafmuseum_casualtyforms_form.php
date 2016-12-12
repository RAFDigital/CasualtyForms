<?php namespace RafMuseum\CasualtyForms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateRafmuseumCasualtyformsForm extends Migration
{
    public function up()
    {
        Schema::table('rafmuseum_casualtyforms_form', function($table)
        {
            $table->increments('id')->unsigned(false)->change();
        });
    }
    
    public function down()
    {
        Schema::table('rafmuseum_casualtyforms_form', function($table)
        {
            $table->increments('id')->unsigned()->change();
        });
    }
}
