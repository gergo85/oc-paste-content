<?php namespace Indikator\Paste\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class ChangeVarcharLength extends Migration
{
    public function up()
    {
        Schema::table('indikator_paste_block', function($table)
        {
            $table->string('button_link', 191)->change();
            $table->string('image', 191)->change();
        });

        Schema::table('indikator_paste_block_category', function($table)
        {
            $table->string('comment', 191)->change();
        });

        Schema::table('indikator_paste_text', function($table)
        {
            $table->string('comment', 191)->change();
        });

        Schema::table('indikator_paste_code', function($table)
        {
            $table->string('comment', 191)->change();
        });
    }

    public function down()
    {
        Schema::table('indikator_paste_block', function($table)
        {
            $table->string('button_link', 200)->change();
            $table->string('image', 200)->change();
        });

        Schema::table('indikator_paste_block_category', function($table)
        {
            $table->string('comment', 500)->change();
        });

        Schema::table('indikator_paste_text', function($table)
        {
            $table->string('comment', 500)->change();
        });

        Schema::table('indikator_paste_code', function($table)
        {
            $table->string('comment', 500)->change();
        });
    }
}
