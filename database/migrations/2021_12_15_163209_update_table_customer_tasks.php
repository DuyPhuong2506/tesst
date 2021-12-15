<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableCustomerTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_tasks', function (Blueprint $table) {
            $table->dropColumn('customer_id');
            $table->dropColumn('task_name');
            $table->string('name')->nullable()->default(null)->after('id');
            $table->string('description')->nullable()->default(null)->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_tasks', function (Blueprint $table) {
            $table->string('task_name')->nullable()->default(null)->after('id');
            $table->bigInteger('customer_id')->after('task_name');
            $table->dropColumn('name');
            $table->dropColumn('description');
        });
    }
}
