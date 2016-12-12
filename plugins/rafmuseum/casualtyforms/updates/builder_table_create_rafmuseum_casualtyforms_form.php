<?php namespace RafMuseum\CasualtyForms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateRafmuseumCasualtyformsForm extends Migration
{
    public function up()
    {
        Schema::create('rafmuseum_casualtyforms_form', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('rank');
            $table->string('first_name');
            $table->string('regiment_corps')->nullable();
            $table->date('report_date_first')->nullable();
            $table->date('report_date_last')->nullable();
            $table->date('death_date')->nullable();
            $table->boolean('details');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('rafmuseum_casualtyforms_form');
    }
}
