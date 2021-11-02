<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeignKeyToTableAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('table_accounts', function (Blueprint $table) {
            $table->unsignedBigInteger('place_id')->nullable()->change();
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
        Schema::table('table_accounts', function (Blueprint $table) {
            $table->dropForeign(['place_id']);
            $table->bigInteger('place_id')->nullable()->change();
        });
    }
}
