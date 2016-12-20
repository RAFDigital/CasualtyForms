<?php namespace RafMuseum\CasualtyForms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateRafmuseumCasualtyformsForms4 extends Migration
{
    public function up()
    {
        Schema::table('rafmuseum_casualtyforms_forms', function($table)
        {
            $table->boolean('approved')->default(0);
            $table->boolean('completed')->default(0);
            $table->integer('completed_by')->nullable();
            $table->string('rank', 255)->nullable()->change();
            $table->string('first_name', 255)->nullable()->change();
            $table->dropColumn('needs_review');
        });
    }
    
    public function down()
    {
        Schema::table('rafmuseum_casualtyforms_forms', function($table)
        {
            $table->dropColumn('approved');
            $table->dropColumn('completed');
            $table->dropColumn('completed_by');
            $table->string('rank', 255)->nullable(false)->change();
            $table->string('first_name', 255)->nullable(false)->change();
            $table->boolean('needs_review')->default(1);
        });
    }
}
