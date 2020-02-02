<?php

use Illuminate\Database\Seeder;
use App\User;

class OrganizationUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function ($user) {
            DB::table('organization_users')->insert([
                'organization_id' => DB::table('organizations')->first()->organization_id,
                'user_id' => $user->user_id,
                'created_at' => \Carbon\Carbon::now(),
            ]);
        });
    }
}
