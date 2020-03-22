<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            [
                'user_id' => (string) Str::uuid(),
                'name' => 'Andy Tan',
                'email' => 'andy.t@covergenius.com',
                'password' => 'abc123',
                'created_at' => Carbon::now()
            ]
        );

        DB::table('users')->insert(
            [
                'user_id' => (string) Str::uuid(),
                'name' => 'Scott Flack',
                'email' => 'scottflack@covergenius.com',
                'password' => 'abc123',
                'created_at' => Carbon::now()
            ]
        );

    }
}
