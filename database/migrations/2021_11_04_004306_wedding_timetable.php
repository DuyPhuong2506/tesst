<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WeddingTimetable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wedding_timetable', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->time('start');
            $table->time('end');
            $table->string('description',200);
            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('id')->on('wedding_test');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('wedding_timetable');
    }
}
