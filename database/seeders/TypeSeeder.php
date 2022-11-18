<?php

namespace Database\Seeders;

use App\Models\SectionType;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types=[
            'about','terms','privacy','why-awj','banner-section','numbers','article'
        ];
    foreach ($types as $type) {
            SectionType::create([
                'type_name' => $type,
            ]);
        }
    }
}