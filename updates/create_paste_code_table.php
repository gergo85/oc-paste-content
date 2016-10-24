<?php namespace Indikator\Paste\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class CreatePasteCodeTable extends Migration
{
    public function up()
    {
        Schema::create('indikator_paste_code', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('code', 100);
            $table->string('name', 100);
            $table->text('content');
            $table->string('common', 500)->nullable();
            $table->string('type', 1)->default(1);
            $table->string('status', 1)->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('indikator_paste_code');
    }
}
