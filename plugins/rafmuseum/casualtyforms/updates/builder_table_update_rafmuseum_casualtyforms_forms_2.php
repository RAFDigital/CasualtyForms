<?php namespace RafMuseum\CasualtyForms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateRafmuseumCasualtyformsForms2 extends Migration
{
    public function up()
    {
        Schema::table('rafmuseum_casualtyforms_forms', function($table)
        {
            $table->boolean('details')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('rafmuseum_casualtyforms_forms', function($table)
        {
            $table->boolean('details')->nullable(false)->change();
        });
    }
}
