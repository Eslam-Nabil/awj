<?php

namespace Database\Seeders;

use App\Models\Pages;
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
        $pages=['home','about us'];
        foreach($pages as $page){
            Pages::create([
                'page_name' =>$page ,
                'slug' => str_replace(' ','-',$page),
            ]);
       }
}
}