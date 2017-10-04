<?php namespace Indikator\Paste\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class ChangeColumnsType extends Migration
{
    public function up()
    {
        Schema::table('indikator_paste_block', function($table)
        {
            $table->integer('sort_order')->change();
        });
    }

    public function down()
    {
        Schema::table('indikator_paste_block', function($table)
        {
            $table->string('sort_order')->change();
        });
    }
}
