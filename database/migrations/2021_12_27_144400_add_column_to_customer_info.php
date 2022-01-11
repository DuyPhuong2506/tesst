<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToCustomerInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_infos', function (Blueprint $table) {
            $table->tinyInteger('email_status')
                ->nullable()
                ->default(null)
                ->after('is_send_wedding_card');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_infos', function (Blueprint $table) {
            $table->dropColumn('email_status');
        });
    }
}
