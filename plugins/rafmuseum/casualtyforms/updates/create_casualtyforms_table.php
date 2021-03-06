<?php namespace RafMuseum\CasualtyForms\Updates;

use DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class CreateRafmuseumCasualtyformsFormTable extends Migration
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
            $table->date('birth_date')->nullable();
            $table->string('regiment_corps')->nullable();
            $table->date('report_date_first')->nullable(); // Not used
            $table->date('report_date_last')->nullable(); // Not used
            $table->date('death_date')->nullable();
            $table->boolean('medical_information')->default(0); // Not used
            $table->integer('started_by_id')->nullable();
            $table->integer('completed_by_id')->nullable();
            $table->integer('approved_by_id')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->string('filename')->nullable();
            $table->boolean('child_form')->default(0);
            $table->integer('parent_form_id')->nullable();
            $table->boolean('flagged')->default(0);
            $table->text('flagged_notes')->nullable();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE rafmuseum_casualtyforms_forms ADD FULLTEXT fullname_index (first_names, surname)');
    }

    public function down()
    {
        Schema::dropIfExists('rafmuseum_casualtyforms_forms');
    }
}
