<?php

use Illuminate\Database\Seeder;

class SportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sports')->insert([
            'sport_id' => (string) Str::uuid(),
            'name' => 'FIFA',
            'created_at' => \Carbon\Carbon::now(),
        ]);
    }
}
