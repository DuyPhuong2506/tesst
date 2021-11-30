<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('wedding_id')->nullable();
            $table->string('rtc_token')->nullable();
            $table->string('rtm_token')->nullable();
            $table->string('name')->nullable();
            $table->string('amount')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->string('type')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->tinyInteger('role')->nullable();
            $table->foreign('wedding_id')->references('id')->on('weddings')->onDelete('cascade');
            $table->softDeletes();
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
        Schema::dropIfExists('channels');
    }
}
