<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTournamentTable extends Migration
{
    const TABLE_NAME = 'tournaments';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {

            $table->uuid('tournament_id')->primary();
            $table->string('name', 255)->nullable();
            $table->string('location', 255)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('tournament_type', 255)->nullable();
            $table->integer('rounds')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
}
