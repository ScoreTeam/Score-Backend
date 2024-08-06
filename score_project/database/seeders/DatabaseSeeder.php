<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $user = \App\Models\User::factory()->create();
        $user->is_admin = true;
        $user->first_name = "John";
        $user->email = "john@example.com";
        $user->save();
        \App\Models\Service::factory(5)->create();
        \App\Models\Employee::factory(10)->create();
        \App\Models\Photo::factory(30)->create();
        \App\Models\Activity::factory(600)->create();
    }
}
