<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCascadeTimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wedding_timetable', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->unsignedBigInteger('event_id')->nullable()->change();
            $table->foreign('event_id')->references('id')->on('weddings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wedding_timetable', function (Blueprint $table) {
            $table->foreign('event_id')->references('id')->on('weddings');
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
        });
    }
}
