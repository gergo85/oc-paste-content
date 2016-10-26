<?php namespace Indikator\Paste\Updates;

use October\Rain\Database\Updates\Migration;
use DbDongle;

class UpdateTimestampsNullable extends Migration
{
    public function up()
    {
        DbDongle::disableStrictMode();

        DbDongle::convertTimestamps('indikator_paste_text');
        DbDongle::convertTimestamps('indikator_paste_code');
    }

    public function down()
    {
        // ...
    }
}
