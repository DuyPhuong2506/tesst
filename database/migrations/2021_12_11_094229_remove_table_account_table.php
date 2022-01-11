<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveTableAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('table_acounts');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('table_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username')->unique(true);
            $table->string('password');
            $table->tinyInteger('role');
            $table->bigInteger('place_id');
            $table->tinyInteger('status')->default(1);
            $table->string('token')->nullable()->default(null)->after('password');
            $table->tinyInteger('type')->nullable()->default(null)->after('token');
            $table->tinyInteger('link_issue')->nullable()->default(null)->after('status');
            $table->tinyInteger('qr_code_image')->nullable()->default(null)->after('link_issue');
            $table->timestamps();
        });
    }
}
