<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Service::factory(5)->create();
        \App\Models\Employee::factory(10)->create();
        \App\Models\Activity::factory(100)->create();
    }
}
