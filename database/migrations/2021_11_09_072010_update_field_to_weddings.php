<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFieldToWeddings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropColumn('schedule_starttime');
            $table->dropColumn('schedule_endtime');
            $table->dropColumn('is_close');
            $table->dropColumn('note');
            $table->dropColumn('img_couple');
            $table->dropColumn('thank_msg');
            $table->string('event_name', 200);
            $table->date('date')->nullable();
            $table->time('welcome_start')->nullable();
            $table->time('welcome_end')->nullable();
            $table->time('wedding_start')->nullable();
            $table->time('wedding_end')->nullable();
            $table->time('reception_start')->nullable();
            $table->time('reception_end')->nullable();
            $table->string('groom_name')->nullable();
            $table->string('groom_email')->nullable();
            $table->string('bride_name')->nullable();
            $table->string('bride_email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weddings');
    }
}
