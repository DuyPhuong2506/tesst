<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToTableAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('table_accounts', function (Blueprint $table) {
            $table->string('token')->nullable()->default(null)->after('password');
            $table->tinyInteger('type')->nullable()->default(null)->after('token');
            $table->tinyInteger('link_issue')->nullable()->default(null)->after('status');
            $table->tinyInteger('qr_code_image')->nullable()->default(null)->after('link_issue');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('table_accounts', function (Blueprint $table) {
            $table->dropColumn('token');
            $table->dropColumn('type');
            $table->dropColumn('link_issue');
            $table->dropColumn('qr_code_image');
        });
    }
}
