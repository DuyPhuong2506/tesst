<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnWeddingCard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wedding_cards', function (Blueprint $table) {
            $table->dropColumn('card_url');
            $table->unsignedBigInteger('template_card_id')->nullable()->default(null);
            $table->foreign('template_card_id')->references('id')->on('template_cards')->onDelete('set NULL');  
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
            $table->string('card_url')->nullable()->default(null);
            $table->dropForeign(['template_card_id']);
            $table->dropColumn('template_card_id');
        });
    }
}
