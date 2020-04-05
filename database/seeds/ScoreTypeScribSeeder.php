<?php

use Illuminate\Database\Seeder;

class ScoreTypeScribSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('score_types')->insert([
            'score_type_id' => (string) Str::uuid(),
            'name' => 'Versus',
            'code' => 'VER',
            'created_at' => \Carbon\Carbon::now(),
        ]);

        DB::table('score_types')->insert([
            'score_type_id' => (string) Str::uuid(),
            'name' => 'Rumble',
            'code' => 'RUM',
            'created_at' => \Carbon\Carbon::now(),
        ]);



        DB::table('sports')->insert([
            'sport_id' => (string) Str::uuid(),
            'name' => 'Scribbl',
            'code' => 'SCRIB',
            'score_type_id' => DB::table('score_types')->where('code', 'RUM')->first()->score_type_id,
            'created_at' => \Carbon\Carbon::now(),
        ]);

        DB::table('leagues')->insert([
            'league_id' => (string) Str::uuid(),
            'name' => 'Cover Genius Scribbl League',
            'organization_id' => DB::table('organizations')->first()->organization_id,
            'sport_id' => DB::table('sports')->where('code', 'SCRIB')->first()->sport_id,
            'created_at' => \Carbon\Carbon::now(),
        ]);
    }
}
