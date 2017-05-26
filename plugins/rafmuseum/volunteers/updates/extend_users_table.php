<?php namespace RafMuseum\Volunteers\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class ExtendUsersTable extends Migration
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
            $table->integer('sessions')->default(0);
        });
    }

    public function down()
    {
        Schema::table('users', function($table) {
            $table->dropColumn([
                'age',
                'sex',
                'country_id',
                'state_id',
                'ethnicity',
                'last_activity',
                'sessions'
            ]);
        });
    }
}
