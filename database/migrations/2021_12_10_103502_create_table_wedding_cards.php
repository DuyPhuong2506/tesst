<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableWeddingCards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wedding_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('card_url')->nullable()->default(null);
            $table->string('content')->nullable()->default(null);
            $table->string('img_url')->nullable()->default(null);
            $table->tinyInteger('status');
            $table->tinyInteger('wedding_price');
            $table->unsignedBigInteger('wedding_id')->nullable()->default(null);
            $table->foreign('wedding_id')->references('id')->on('weddings')->onDelete('set NULL');
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
        Schema::table('wedding_cards', function (Blueprint $table) {
            $table->dropForeign(['wedding_id']);
        });
        Schema::dropIfExists('wedding_cards');
    }
}
