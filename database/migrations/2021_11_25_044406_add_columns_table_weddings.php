<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsTableWeddings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->tinyInteger('allow_remote')->nullable()->default(null);
            $table->date('guest_invitation_response_date')->nullable()->default(null);
            $table->date('couple_edit_date')->nullable()->default(null);
            $table->date('couple_invitation_edit_date')->nullable()->default(null);
            $table->date('ceremony_confirm_date')->nullable()->default(null);
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
            $table->dropColumn('allow_remote');
            $table->dropColumn('guest_invitation_response_date');
            $table->dropColumn('couple_edit_date');
            $table->dropColumn('couple_invitation_edit_date');
            $table->dropColumn('ceremony_confirm_date');
        });
    }
}
