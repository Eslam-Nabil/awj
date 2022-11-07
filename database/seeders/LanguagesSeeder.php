<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages=['english','arabic'];
        foreach ($languages as $lang ) {
            Language::create([
            'title'=>$lang,
            'code'=>substr($lang,0,2)
            ]);
        }
    }
}