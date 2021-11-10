<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('address_1');
            $table->dropColumn('address_2');
            $table->dropColumn('company_name');
            $table->dropColumn('contact_email');
            $table->dropColumn('ceremony_name');
            $table->dropColumn('charge_name');
            $table->dropColumn('portal_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('address_1');
            $table->dropColumn('address_2');
            $table->dropColumn('company_name');
            $table->dropColumn('contact_email');
            $table->dropColumn('ceremony_name');
            $table->dropColumn('charge_name');
            $table->dropColumn('portal_code');
        });
    }
}
