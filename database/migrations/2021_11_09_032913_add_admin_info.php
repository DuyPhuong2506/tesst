<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('ceremony_name', 50)->nullable()->default(null);
            $table->string('charge_name', 100)->nullable()->default(null);
            $table->string('contact_email', 100)->nullable()->default(null);
            $table->string('portal_code', 50)->nullable()->default(null);
            $table->dropColumn('address');
            $table->string('address_1', 255)->nullable()->default(null);
            $table->string('address_2', 255)->nullable()->default(null);
            $table->dateTime('lasted_login')->nullable()->default(null);
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
            $table->dropColumn('ceremony_name');
            $table->dropColumn('charge_name');
            $table->dropColumn('contact_email');
            $table->dropColumn('portal_code');
            $table->dropColumn('address');
            $table->dropColumn('address_1');
            $table->dropColumn('address_2');
            $table->dropColumn('lasted_login');
        });
    }
}
