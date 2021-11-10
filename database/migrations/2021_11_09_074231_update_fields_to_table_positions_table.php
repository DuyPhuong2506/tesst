<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFieldsToTablePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('table_positions', function (Blueprint $table) {
            $table->dropColumn('table_account_id');
            $table->integer('amount_chair')->nullable();
            $table->string('position')->nullable()->change();
            $table->unsignedBigInteger('place_id')->nullable();
            $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('table_positions', function (Blueprint $table) {
            $table->dropForeign('place_id');
            $table->dropColumn('place_id');
            $table->bigInteger('table_account_id')->change();
            $table->dropColumn('amount_chair');
            $table->integer('position')->unsigned(true)->change();
        });
    }
}
