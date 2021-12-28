<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldTablePositionIdToChannels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->string('display_name')->nullable();
            $table->unsignedBigInteger('table_position_id')->nullable();
            $table->foreign('table_position_id')->references('id')->on('table_positions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->dropForeign(['table_position_id']);
            $table->dropColumn('table_position_id');
            $table->dropColumn('display_name');
        });
    }
}
