<?php namespace RafMuseum\CasualtyForms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateRafmuseumCasualtyformsForms10 extends Migration
{
    public function up()
    {
        Schema::table('rafmuseum_casualtyforms_forms', function($table)
        {
            $table->integer('completed_by')->nullable();
            $table->integer('approved_by')->nullable();
            $table->dropColumn('completed_by_id');
            $table->dropColumn('approved_by_id');
        });
    }
    
    public function down()
    {
        Schema::table('rafmuseum_casualtyforms_forms', function($table)
        {
            $table->dropColumn('completed_by');
            $table->dropColumn('approved_by');
            $table->integer('completed_by_id')->nullable();
            $table->integer('approved_by_id')->nullable();
        });
    }
}
