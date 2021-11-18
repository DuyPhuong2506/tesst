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
        Schema::table('weddings', function (Blueprint $table) {
            $table->string('event_name', 200);
            $table->time('welcome_start')->nullable();
            $table->time('welcome_end')->nullable();
            $table->time('wedding_start')->nullable();
            $table->time('wedding_end')->nullable();
            $table->time('reception_start')->nullable();
            $table->time('reception_end')->nullable();
            $table->string('groom_name')->nullable();
            $table->string('groom_email')->nullable();
            $table->string('bride_name')->nullable();
            $table->string('bride_email')->nullable();
            $table->dropColumn('title');
            $table->dropColumn('pic_name');
            $table->dropColumn('ceremony_reception_time');
            $table->dropColumn('ceremony_time');
            $table->dropColumn('party_reception_time');
            $table->dropColumn('party_time');
            $table->dropColumn('is_close');
            $table->dropColumn('table_map_image');
            $table->dropColumn('greeting_message');
            $table->dropColumn('thank_you_message');
        });
    }
}
