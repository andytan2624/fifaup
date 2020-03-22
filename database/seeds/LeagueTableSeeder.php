<?php

use Illuminate\Database\Seeder;
use App\Organization;
use Illuminate\Support\Facades\DB;

class LeagueTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('leagues')->insert([
            'league_id' => (string) Str::uuid(),
            'name' => 'FIFA League',
            'organization_id' => DB::table('organizations')->first()->organization_id,
            'sport_id' => DB::table('sports')->where('code', 'FIFA')->first()->sport_id,
            'created_at' => \Carbon\Carbon::now(),
        ]);

        DB::table('leagues')->insert([
            'league_id' => (string) Str::uuid(),
            'name' => 'Cover Genius Table Tennis League',
            'organization_id' => DB::table('organizations')->first()->organization_id,
            'sport_id' => DB::table('sports')->where('code', 'TT')->first()->sport_id,
            'created_at' => \Carbon\Carbon::now(),
        ]);
    }
}
