<?php namespace Indikator\Paste\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class AddColorFieldToBlockTable extends Migration
{
    public function up()
    {
        Schema::table('indikator_paste_block', function($table)
        {
            $table->string('color', 7)->nullable();
        });
    }

    public function down()
    {
        Schema::table('indikator_paste_block', function($table)
        {
            $table->dropColumn('color');
        });
    }
}
