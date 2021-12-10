<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBankAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('bank_name')->nullable()->default(null);
            $table->string('bank_branch')->nullable()->default(null);
            $table->string('account_number')->nullable()->default(null);
            $table->string('card_type')->nullable()->default(null);
            $table->string('holder_name')->nullable()->default(null);
            $table->unsignedBigInteger('wedding_card_id')->nullable()->default(null);
            $table->foreign('wedding_card_id')->references('id')->on('wedding_cards')->onDelete('set NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->dropForeign(['wedding_card_id']);
        });
        Schema::dropIfExists('bank_accounts');
    }
}
