<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCustomerInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('is_only_party')->nullable()->default(null);
            $table->string('first_name')->nullable()->default(null);
            $table->string('last_name')->nullable()->default(null);
            $table->string('relationship_couple')->nullable()->default(null);
            $table->string('phone')->nullable()->default(null);
            $table->string('post_code')->nullable()->default(null);
            $table->string('address')->nullable()->default(null);
            $table->tinyInteger('customer_type')->nullable()->default(null);
            $table->string('task_content')->nullable()->default(null);
            $table->string('free_word')->nullable()->default(null);
            $table->tinyInteger('is_send_wedding_card')->nullable()->default(null);
            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->foreign('bank_account_id')
                  ->references('id')
                  ->on('bank_accounts')
                  ->onDelete('set NULL');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')
                  ->references('id')
                  ->on('customers')
                  ->onDelete('set NULL');
            $table->timestamps();
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
            $table->dropForeign(['bank_account_id', 'customer_id']);
        })->dropIfExists('customer_infos');
    }
}
