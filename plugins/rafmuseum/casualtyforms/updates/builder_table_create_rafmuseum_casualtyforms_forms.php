<?php namespace RafMuseum\CasualtyForms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateRafmuseumCasualtyformsForm extends Migration
{
    public function up()
    {
        Schema::create('rafmuseum_casualtyforms_forms', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned(false);
            $table->string('rank')->nullable();
            $table->string('first_names')->nullable();
            $table->string('surname')->nullable();
            $table->string('regiment_corps')->nullable();
            $table->date('report_date_first')->nullable();
            $table->date('report_date_last')->nullable();
            $table->date('death_date')->nullable();
            $table->boolean('medical_information')->nullable();
            $table->integer('started_by_id')->nullable();
            $table->integer('completed_by_id')->nullable();
            $table->integer('approved_by_id')->nullable();
            $table->string('filename')->nullable();
            $table->boolean('additional_page')->default(0);
            $table->boolean('parent_page_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rafmuseum_casualtyforms_forms');
    }
}
