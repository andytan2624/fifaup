<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationTable extends Migration
{
    const TABLE_NAME = 'registrations';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->uuid('registration_id')->primary();
            $table->uuid('user_id')->nullable();
            $table->uuid('tournament_id')->nullable();
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null')->onUpdate('CASCADE');
            $table->foreign('tournament_id')->references('tournament_id')->on('tournaments')->onDelete('set null')->onUpdate('CASCADE');

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
