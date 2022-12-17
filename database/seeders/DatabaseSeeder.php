<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\PagesSeeder;
use Database\Seeders\PermissionRoleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionRoleSeeder::class,
            LanguagesSeeder::class,
            UserSeeder::class,
            PagesSeeder::class,
            TypeSeeder::class,
            ContactSeeder::class,
        ]);
    }
}
