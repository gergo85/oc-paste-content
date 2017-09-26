<?php namespace Indikator\Paste\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class CreatePasteBlockTable extends Migration
{
    public function up()
    {
        Schema::create('indikator_paste_block', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('code', 100);
            $table->string('name', 100);
            $table->string('title', 100)->nullable();
            $table->string('subtitle', 100)->nullable();
            $table->text('content')->nullable();
            $table->string('button_name', 50)->nullable();
            $table->string('button_link', 200)->nullable();
            $table->string('button_class', 100)->nullable();
            $table->string('button_position', 10)->nullable();
            $table->string('image', 200)->nullable();
            $table->string('category', 4)->default(0);
            $table->string('sort_order', 3)->default(1);
            $table->string('status', 1)->default(1);
            $table->timestamps();
        });

        Schema::create('indikator_paste_block_category', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('code', 100);
            $table->string('name', 100);
            $table->string('common', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('indikator_paste_block');
        Schema::dropIfExists('indikator_paste_block_category');
    }
}
