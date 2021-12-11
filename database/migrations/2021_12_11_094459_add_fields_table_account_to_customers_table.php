<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsTableAccountToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('chair_name')->nullable()->default(null)->after('address');
            $table->string('link_issue')->nullable()->default(null)->after('chair_name');
            $table->string('qr_code_image')->nullable()->default(null)->after('link_issue');
            $table->unsignedBigInteger('place_id')->nullable()->after('qr_code_image');
            $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade');
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
            $table->dropColumn('chair_name');
            $table->dropColumn('link_issue');
            $table->dropColumn('qr_code_image');
            $table->dropForeign('place_id');
            $table->dropColumn('place_id');
        });
    }
}
