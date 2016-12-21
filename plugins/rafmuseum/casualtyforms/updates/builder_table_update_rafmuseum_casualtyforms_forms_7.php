<?php namespace RafMuseum\CasualtyForms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateRafmuseumCasualtyformsForms7 extends Migration
{
    public function up()
    {
        Schema::table('rafmuseum_casualtyforms_forms', function($table)
        {
            $table->integer('approved_by')->nullable()->default(0);
            $table->integer('completed_by')->default(0)->change();
            $table->dropColumn('approved');
            $table->dropColumn('completed');
        });
    }
    
    public function down()
    {
        Schema::table('rafmuseum_casualtyforms_forms', function($table)
        {
            $table->dropColumn('approved_by');
            $table->integer('completed_by')->default(null)->change();
            $table->boolean('approved')->default(0);
            $table->boolean('completed')->default(0);
        });
    }
}
