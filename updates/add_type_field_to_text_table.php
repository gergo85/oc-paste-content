<?php namespace Indikator\Paste\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class AddTypeFieldToTextTable extends Migration
{
    public function up()
    {
        Schema::table('indikator_paste_text', function($table)
        {
            $table->string('type', 1)->default(2);
        });
    }

    public function down()
    {
        Schema::table('indikator_paste_text', function($table)
        {
            $table->dropColumn('type');
        });
    }
}
