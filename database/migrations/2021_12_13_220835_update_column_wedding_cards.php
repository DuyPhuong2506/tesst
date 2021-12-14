<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnWeddingCards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wedding_cards', function (Blueprint $table) {
            $table->dropColumn('img_url');
            $table->string('couple_photo')->nullable()->default(null)->after('card_url');
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
            $table->string('img_url')->nullable()->default(null);
            $table->dropColumn('couple_photo');
        });
    }
}
