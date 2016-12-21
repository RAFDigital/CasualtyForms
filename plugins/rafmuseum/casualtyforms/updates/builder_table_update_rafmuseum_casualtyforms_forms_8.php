<?php namespace RafMuseum\CasualtyForms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateRafmuseumCasualtyformsForms8 extends Migration
{
    public function up()
    {
        Schema::table('rafmuseum_casualtyforms_forms', function($table)
        {
            $table->integer('completed_by')->default(null)->change();
            $table->integer('approved_by')->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('rafmuseum_casualtyforms_forms', function($table)
        {
            $table->integer('completed_by')->default(0)->change();
            $table->integer('approved_by')->default(0)->change();
        });
    }
}
