<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrganizationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organizations')->insert([
            'organization_id' => (string) Str::uuid(),
            'name' => 'Cover Genius',
            'created_at' => \Carbon\Carbon::now(),
        ]);
    }
}
