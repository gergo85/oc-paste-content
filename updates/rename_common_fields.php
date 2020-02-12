<?php namespace Indikator\Paste\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class RenameCommonFields extends Migration
{
    public function up()
    {
        Schema::table('indikator_paste_block_category', function($table)
        {
            $table->renameColumn('common', 'comment');
        });

        Schema::table('indikator_paste_text', function($table)
        {
            $table->renameColumn('common', 'comment');
        });

        Schema::table('indikator_paste_code', function($table)
        {
            $table->renameColumn('common', 'comment');
        });
    }

    public function down()
    {
        Schema::table('indikator_paste_block_category', function($table)
        {
            $table->renameColumn('comment', 'common');
        });

        Schema::table('indikator_paste_text', function($table)
        {
            $table->renameColumn('comment', 'common');
        });

        Schema::table('indikator_paste_code', function($table)
        {
            $table->renameColumn('comment', 'common');
        });
    }
}
