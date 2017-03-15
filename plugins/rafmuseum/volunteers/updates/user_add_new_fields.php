<?php namespace RafMuseum\Volunteers\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddNewFields extends Migration
{

    public function up()
    {
        Schema::table('users', function($table) {
            $table->string('age')->nullable();
            $table->string('sex')->nullable();
            $table->string('country_id')->nullable();
            $table->string('state_id')->nullable();
            $table->string('ethnicity')->nullable();
            $table->timestamp('last_activity')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function($table) {
            $table->dropColumn([
                'age',
                'sex',
                'country_id',
                'ethnicity',
                'last_activity'
            ]);
        });
    }
}
