<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('settings')->truncate();
        Schema::enableForeignKeyConstraints();

        Setting::create([
            'key' => 'login-image',
            'value'=>'images/user.png',
            'type'=>'image'
        ]);
        
        Setting::create([
            'key' => 'slogan',
            'value'=>'this is awj slogan ',
            'type'=>'text'
        ]);

    }
}
