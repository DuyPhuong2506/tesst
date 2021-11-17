<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateWeddingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropColumn('bride_email');
            $table->dropColumn('bride_name');
            $table->dropColumn('groom_email');
            $table->dropColumn('groom_name');
            $table->dropColumn('welcome_start')->nullable()->default(null);
            $table->dropColumn('welcome_end')->nullable()->default(null);
            $table->dropColumn('wedding_start')->nullable()->default(null);
            $table->dropColumn('wedding_end')->nullable()->default(null);
            $table->dropColumn('reception_start')->nullable()->default(null);
            $table->dropColumn('reception_end')->nullable()->default(null);
            $table->dropColumn('event_name')->nullable()->default(null);
            $table->string('title')->nullable()->default(null);
            $table->string('pic_name')->nullable()->default(null);
            $table->string('ceremony_reception_time')->nullable()->default(null);
            $table->string('ceremony_time')->nullable()->default(null);
            $table->string('party_reception_time')->nullable()->default(null);
            $table->string('party_time')->nullable()->default(null);
            $table->tinyInteger('is_close')->nullable()->default(null);
            $table->string('table_map_image')->nullable()->default(null);
            $table->string('greeting_message')->nullable()->default(null);
            $table->text('thank_you_message')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weddings');
    }
}
