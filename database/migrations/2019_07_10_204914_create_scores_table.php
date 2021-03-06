<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateScoresTable extends Migration
{
    const TABLE_NAME = 'scores';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(static::TABLE_NAME, function (Blueprint $table) {
            $table->uuid('score_id')->primary();
            $table->uuid('match_id');
            $table->uuid('user_id');
            $table->integer('points')->nullable()->default(0);
            $table->enum('status', ['win', 'loss', 'draw', 'pending', 'void'])->nullable();
            $table->enum('team', ['1', '2'])->nullable();
            $table->boolean('is_team')->default(0);
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('match_id', 'match_fk')->references('match_id')->on('matches')->onDelete('CASCADE
')->onUpdate('CASCADE');
            $table->foreign('user_id', 'user_fk')->references('user_id')->on('users')->onDelete('CASCADE
')->onUpdate('CASCADE');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(static::TABLE_NAME);
    }
}
