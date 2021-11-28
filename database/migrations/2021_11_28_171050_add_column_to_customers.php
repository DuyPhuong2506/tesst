<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('invitation_url')
                  ->nullable()
                  ->default(null)
                  ->after('remember_token');
            $table->unsignedBigInteger('wedding_id')->nullable()->change();
            $table->foreign('wedding_id')
                  ->references('id')
                  ->on('weddings')
                  ->onDelete('set null');
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
            $table->dropForeign(['wedding_id']);
            $table->dropColumn('invitation_url');
        });
    }
}
