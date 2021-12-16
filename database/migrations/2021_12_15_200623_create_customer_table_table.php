<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerTableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_table', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('chair_name')->nullable()->default(null);
            $table->unsignedBigInteger('table_position_id')->nullable();
            $table->foreign('table_position_id')->references('id')->on('table_positions')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('customer_table');
    }
}
