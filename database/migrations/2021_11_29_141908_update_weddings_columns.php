<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateWeddingsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropColumn('couple_invitation_edit_date');
            $table->dropColumn('ceremony_confirm_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->date('couple_invitation_edit_date')->nullable()->default(null);
            $table->date('ceremony_confirm_date')->nullable()->default(null);
        });
    }
}
