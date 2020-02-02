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
                'name' => 'Mitra Bajrachrya',
                'email' => 'mitra@rentalcover.com',
                'password' => 'abc123',
                'created_at' => Carbon::now()
            ]
        );

        DB::table('users')->insert(
            [
                'user_id' => (string) Str::uuid(),
                'name' => 'Scott Flack',
                'email' => 'scottflick@covergenius.com',
                'password' => 'abc123',
                'created_at' => Carbon::now()
            ]
        );

        DB::table('users')->insert(
            [
                'user_id' => (string) Str::uuid(),
                'name' => 'Artem Kaleshnikov',
                'email' => 'artem@covergenius.com',
                'password' => 'abc123',
                'created_at' => Carbon::now()
            ]
        );

        DB::table('users')->insert(
            [
                'user_id' => (string) Str::uuid(),
                'name' => 'Valentin Darricau',
                'email' => 'valentin.d@covergenius.com',
                'password' => 'abc123',
                'created_at' => Carbon::now()
            ]
        );

        DB::table('users')->insert(
            [
                'user_id' => (string) Str::uuid(),
                'name' => 'Chris Agiasotis',
                'email' => 'chris.a@covergenius.com',
                'password' => 'abc123',
                'created_at' => Carbon::now()
            ]
        );

        DB::table('users')->insert(
            [
                'user_id' => (string) Str::uuid(),
                'name' => 'Damian Marcos',
                'email' => 'damian@covergenius.com',
                'password' => 'abc123',
                'created_at' => Carbon::now()
            ]
        );

        DB::table('users')->insert(
            [
                'user_id' => (string) Str::uuid(),
                'name' => 'Udit Agarwal',
                'email' => 'udit@covergenius.com',
                'password' => 'abc123',
                'created_at' => Carbon::now()
            ]
        );
    }
}
