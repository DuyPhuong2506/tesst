<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToRestaurants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->integer('guest_invitation_response_num')->nullable()->default(null);
            $table->integer('couple_edit_num')->nullable()->default(null);
            $table->integer('couple_invitation_edit_num')->nullable()->default(null);
            $table->integer('ceremony_confirm_num')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn('guest_invitation_response_num');
            $table->dropColumn('couple_edit_num');
            $table->dropColumn('couple_invitation_edit_num');
            $table->dropColumn('ceremony_confirm_num');
        });
    }
}
