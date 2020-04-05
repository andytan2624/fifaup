<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSportsTableToHandleScoringType extends Migration
{
    const TABLE_NAME = 'sports';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->uuid('score_type_id')->nullable()->after('code');

            $table->foreign('score_type_id')->references('score_type_id')->on('score_types')->onDelete('set null')->onUpdate('CASCADE');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->dropForeign('sports_score_type_id_foreign');
            $table->dropColumn('score_type_id');
        });
    }
}
