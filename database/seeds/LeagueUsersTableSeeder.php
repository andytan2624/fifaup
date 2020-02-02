<?php

use Illuminate\Database\Seeder;
use App\User;

class LeagueUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function ($user) {
            DB::table('league_users')->insert([
                'league_id' => DB::table('leagues')->first()->league_id,
                'user_id' => $user->user_id,
                'created_at' => \Carbon\Carbon::now(),
            ]);
        });
    }
}
