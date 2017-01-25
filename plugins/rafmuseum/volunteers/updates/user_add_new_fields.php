<?php namespace RafMuseum\Volunteers\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddNewFields extends Migration
{

    public function up()
    {
        Schema::table('users', function($table)
        {
            $table->date('date_of_birth')->nullable();
            $table->string('sex')->nullable();
            $table->string('location')->nullable();
            $table->string('ethnicity')->nullable();
            $table->timestamp('last_activity')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function($table)
        {
            $table->dropColumn(['date_of_birth', 'sex', 'location', 'ethnicity', 'last_activity']);
        });
    }

}
