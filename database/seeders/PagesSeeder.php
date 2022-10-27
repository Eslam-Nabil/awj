<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $page = Pages::create([
            'page_name' => 'Home',
            'slug' => 'home',
        ]);
        $page = Pages::create([
            'page_name' => 'about',
            'slug' => 'about-us',
        ]);
    }
}
