<?php namespace RafMuseum\CasualtyForms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateRafmuseumCasualtyformsForms extends Migration
{
    public function up()
    {
        Schema::rename('rafmuseum_casualtyforms_form', 'rafmuseum_casualtyforms_forms');
    }
    
    public function down()
    {
        Schema::rename('rafmuseum_casualtyforms_forms', 'rafmuseum_casualtyforms_form');
    }
}
