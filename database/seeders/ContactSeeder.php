<?php

namespace Database\Seeders;

use App\Models\Contactus;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Contactus::create([
            'phone_number_1'=>"012356985",
            ]);
    }
}
