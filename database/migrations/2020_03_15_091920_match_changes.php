<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MatchChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn('team_1_score');
            $table->dropColumn('team_2_score');
            $table->dropColumn('team_1_penalties');
            $table->dropColumn('team_2_penalties');
            $table->dropColumn('name');
            $table->boolean('is_tournament')->default(0)->after('league_id');
            $table->dateTime('scheduled_date')->nullable()->after('is_tournament');
            $table->uuid('tournament_id')->nullable()->after('scheduled_date');
            $table->uuid('first_registration_id')->nullable()->after('tournament_id');
            $table->uuid('second_registration_id')->nullable()->after('first_registration_id');

            $table->foreign('tournament_id')->references('tournament_id')->on('tournaments')->onDelete('set null')->onUpdate('CASCADE');
            $table->foreign('first_registration_id')->references('registration_id')->on('registrations')->onDelete('set null')->onUpdate('CASCADE');
            $table->foreign('second_registration_id')->references('registration_id')->on('registrations')->onDelete('set null')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->integer('team_1_score')->nullable()->default(0)->after('league_id');
            $table->integer('team_2_score')->nullable()->default(0)->after('team_1_score');
            $table->integer('team_1_penalties')->nullable()->default(0)->after('team_2_score');
            $table->integer('team_2_penalties')->nullable()->default(0)->after('team_1_penalties');
            $table->string('name')->nullable()->after('team_2_penalties');

            $table->dropForeign('matches_first_registration_id_foreign');
            $table->dropForeign('matches_second_registration_id_foreign');
            $table->dropForeign('matches_tournament_id_foreign');

            $table->dropColumn('is_tournament');
            $table->dropColumn('scheduled_date');
            $table->dropColumn('first_registration_id');
            $table->dropColumn('tournament_id');
            $table->dropColumn('second_registration_id');
        });
    }
}
