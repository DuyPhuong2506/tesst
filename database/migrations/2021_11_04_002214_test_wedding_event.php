<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TestWeddingEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wedding_test', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('event_name', 200);
            $table->date('date');
            $table->time('welcome_start');
            $table->time('welcome_end');
            $table->time('wedding_start');
            $table->time('wedding_end');
            $table->time('reception_start');
            $table->time('reception_end');
            $table->unsignedBigInteger('place_id');
            $table->foreign('place_id')->references('id')->on('places');
            $table->string('groom_name', 30);
            $table->string('groom_email', 50);
            $table->string('bride_name', 30);
            $table->string('bride_email', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wedding_test');
    }
}
