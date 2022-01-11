<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePlaceIdToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['place_id']);
            $table->dropColumn('place_id');
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
            $table->unsignedBigInteger('place_id')->nullable()->after('qr_code_image');
            $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade');
        });
    }
}
