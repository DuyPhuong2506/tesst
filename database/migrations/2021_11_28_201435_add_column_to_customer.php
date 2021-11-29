<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToCustomer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dateTime('deleted_at')->nullable()->default(null);
            $table->tinyInteger('join_status')->nullable()->default(null)->after('full_name');
            $table->dateTime('confirmed_at')->nullable()->default(null)->after('full_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
            $table->dropColumn('join_status');
            $table->dropColumn('confirmed_at');
        });
    }
}
