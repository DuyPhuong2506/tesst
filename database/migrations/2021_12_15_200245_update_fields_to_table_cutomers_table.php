<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFieldsToTableCutomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('chair_name');
            $table->dropForeign(['table_position_id']);
            $table->dropColumn('table_position_id');
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
            $table->string('chair_name')->nullable()->default(null)->after('address');
            $table->unsignedBigInteger('table_position_id')->nullable();
            $table->foreign('table_position_id')->references('id')->on('table_positions')->onDelete('set null');
        });
    }
}
