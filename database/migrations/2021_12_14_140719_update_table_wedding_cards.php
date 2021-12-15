<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableWeddingCards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wedding_cards', function (Blueprint $table) {
            $table->unsignedInteger('status')->unsigned()->nullable()->default(null)->change();
            $table->unsignedInteger('wedding_price')->unsigned()->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wedding_cards', function (Blueprint $table) {
            $table->tinyInteger('status')->change();
            $table->tinyInteger('wedding_price')->change();
        });
    }
}
