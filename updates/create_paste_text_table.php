<?php namespace Indikator\Paste\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class CreatePasteTextTable extends Migration
{
    public function up()
    {
        Schema::create('indikator_paste_text', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('code', 100);
            $table->string('name', 100);
            $table->text('content');
            $table->string('common', 500)->nullable();
            $table->string('status', 1)->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('indikator_paste_text');
    }
}
